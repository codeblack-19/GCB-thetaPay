<?php 

    require_once './controllers/RouteConfig/config.php';
    require_once './helpers/validator.php';
    require_once './models/transaction.php';
    require_once './controllers/viewController.php';
    require_once './models/bankcard.php';
    
    $baseName = '/transactions';

    // initiate web payment
    Route::base("$baseName/initiate_payment", function(){
        $middleware = new Middleware;
        if(!$middleware->verifySecreteKey()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("amount", "currency", "webhook", "description");
        $reqbody = json_decode(file_get_contents('php://input'), true);
        if(!$reqbody){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request data"));
            return;
        }

        // validate request body
        $checkValues = new Validator;
        $holdChecks = $checkValues->validateBody($reqbody, $Requiredbody);
        if(!empty($holdChecks)){
            http_response_code(400);
            echo json_encode($holdChecks);
            return;
        }

        $txn = new Transaction;
        $txn->accountNo = $_GET['acct']['accountNo'];
        $txn->currency = $reqbody['currency'];
        $txn->description = $reqbody['description'];
        $txn->webhook = $reqbody['webhook'];
        $txn->status = 'initiated';
        $txn->type = 'webpayment';
        $txn->convertAmt($reqbody['amount']);
        $txn->txn_id = $txn->generateId(10);
        $txn->txn_token = $txn->getTxnToken();
        
        if($txn->saveInitPaymentTxn()){
            $today = date('Y/m/d H:i:s');
            $link = 'http://localhost/GCB-thetaPay/gateway/transactions/paymentpage?signature='.$txn->txn_token.'';
            echo json_encode(array("link"=> "$link", "message"=>"Payment initiated successfully", "txn_id" => "$txn->txn_id", "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 60 minute"))));
            return;
        }else{
            http_response_code(500);
            echo json_encode(array('error'=> 'An error occured please try again'));
            return;
        }
    });

    // web Payment page
    Route::base("$baseName/paymentpage", function(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // required query strings
        $requiredquery = array("signature");
        $queryStrings = array();
        parse_str($_SERVER['QUERY_STRING'], $queryStrings);

        // validate req queries
        $validate = new Validator;
        $holdValues = $validate->validateQueryStrings($queryStrings, $requiredquery);
        if(!empty($holdValues)){
            http_response_code(400);
            ViewController::CreateViewWithParams('transactions/paymentpage?error='.$holdValues[0]['error'].'');
            return;
        }

        $txn = new Transaction;
        $txn->txn_token = $_GET['signature'];
        $txn->status = 'pending';
        $txnArr = $txn->decodeTxnToken();
        $txn->txn_id = $txnArr['txn_id'] ?? '';

        if(!$txnArr){
            ViewController::CreateViewWithParams("transactions/paymentpage?error=Invalid signature");
            return;
        }else if($txn->checkSignatureDate($_GET['signature'])){
            $txn->status = 'failed';
            if($txn->updateTxnStat()){
                ViewController::CreateViewWithParams('transactions/paymentpage?error=Transaction signture has expired');
                return;
            }
        }else{ 
            $txn_info = $txn->getTxnById();
            if($txn_info['status'] == "success" || $txn_info['status'] == "failed"){
                ViewController::CreateViewWithParams("transactions/paymentpage?error=Transaction has been completed");
                return;
            }else if($txn->updateTxnStat()){
                $_GET['amount'] = $txn_info['amount'];
                ViewController::CreateViewWithParams("transactions/paymentpage?message=success");
                return;
            }
        }
        
    });

    // theta-to-theta payment
    Route::base("$baseName/intertransfer", function(){
        header("Content-Type: application/json; charset=UTF-8");

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("accountNo", "pinCode", "signature");
        $reqbody = json_decode(file_get_contents('php://input'), true);
        if(!$reqbody){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request data"));
            return;
        }

        //validate request body
        $checkValues = new Validator;
        $holdChecks = $checkValues->validateBody($reqbody, $Requiredbody);
        if(!empty($holdChecks)){
            http_response_code(400);
            echo json_encode($holdChecks);
            return;
        }
        
        $txn = new Transaction;
        $txn->txn_token = $reqbody['signature'];
        $txnArr = $txn->decodeTxnToken();
        $txn->medium = 'internal';
        $txn->recipient_acctNo = $reqbody['accountNo'];
        $txn->txn_id = $txnArr['txn_id'] ?? '';
        $txn_info = $txn->getTxnById();

        // validate token first
        if(!$txnArr){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid signature"));
            return;
        }else if($txn->checkSignatureDate($reqbody['signature'])){
            $txn->status = 'failed';
            if($txn->updateTxnStat()){
                http_response_code(400);
                echo json_encode(array("error" => "Transaction signture has expired"));
                return;
            } 
        }else if(empty($txn_info)){
            http_response_code(400);
            echo json_encode(array("error" => "Transaction not initiated"));
            return;
        }

        // validate account Number
        $account = new Account;
        $account->accountNo = $reqbody['accountNo'];
        $account->pinCode = (int)$reqbody['pinCode'];
        $accInfo = $account->getAcctById();
        $mail = new MailingService;

        if(empty($accInfo)){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Account Number"));
            return;
        }else if($txn_info['accountNo'] == $reqbody['accountNo']){
            http_response_code(400);
            echo json_encode(array("error" => "Self transfer is not allowed"));
            return;
        }else if(!password_verify($account->pinCode, $accInfo['pinCode'])){
            http_response_code(400);
            echo json_encode(array("error" => 'Invalid Account Pin Code'));
            return;
        }else if($accInfo['balance'] < $txn_info['amount']){
            http_response_code(400);
            echo json_encode(array("error" => "Insufficient balance "));
            return;
        }else if($txn->creditAccount($txn_info['amount'], $txn_info['accountNo']) && $txn->debitAccount($txn_info['amount'], $reqbody['accountNo']) && 
            $txn->insertIntoTransfer()){
            $account1 = new Account;
            $account1->accountNo = $txn_info['accountNo'];
            $account1Info = $account1->getAcctById();
            $accInfo = $account->getAcctById();
            $keys = $account1->getKeysByAcctNo();
            $txn->status = "success";
            $txn->publicKey = $txn->encryptData($keys['publicKey']);
            $txn->updateTxnStat();
            $txn->updateTxnMedium();

            if($txn->sendRespondsToWebhook($txn_info['webhook']) && $mail->transactionNotify($accInfo, $txn_info, 'Debit') && $mail->transactionNotify($account1Info, $txn_info, 'Credit')){
                echo json_encode(array("message" => "Transactions completed successfully"));
                return;
            }else{
                $txn->debitAccount($txn_info['amount'], $txn_info['accountNo']);
                $txn->creditAccount($txn_info['amount'], $reqbody['accountNo']);
                $txn->status = "failed";
                $txn->updateTxnStat();
                $txn->sendRespondsToWebhook($txn_info['webhook']);
                http_response_code(500);
                echo json_encode(array("error" => "An error occured please try again"));
                return;
            }
        }else{
            $txn->debitAccount($txn_info['amount'], $txn_info['accountNo']);
            $txn->creditAccount($txn_info['amount'], $reqbody['accountNo']);
            $txn->status = "failed";
            $txn->updateTxnStat();
            $txn->sendRespondsToWebhook($txn_info['webhook']);
            http_response_code(500);
            echo json_encode(array("error" => "An error occured please try again"));
            return;
        }


    });

    // card payment 
    Route::base("$baseName/cardpayment", function(){
        header("Content-Type: application/json; charset=UTF-8");

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("name", "cardNo", "cvv", "expiry_mm", "expiry_yy", "signature");
        $reqbody = json_decode(file_get_contents('php://input'), true);
        if(!$reqbody){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request data"));
            return;
        }

        //validate request body
        $checkValues = new Validator;
        $holdChecks = $checkValues->validateBody($reqbody, $Requiredbody);
        if(!empty($holdChecks)){
            http_response_code(400);
            echo json_encode($holdChecks);
            return;
        }

        $txn = new Transaction;
        $txn->txn_token = $reqbody['signature'];
        $txnArr = $txn->decodeTxnToken();
        $txn->medium = 'card';
        $txn->cardNo = $reqbody['cardNo'];
        $txn->txn_id = $txnArr['txn_id'] ?? '';
        $txn_info = $txn->getTxnById();

        // validate token first
        if(!$txnArr){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid signature"));
            return;
        }else if($txn->checkSignatureDate($reqbody['signature'])){
            $txn->status = 'failed';
            if($txn->updateTxnStat()){
                http_response_code(400);
                echo json_encode(array("error" => "Transaction signture has expired"));
                return;
            } 
        }else if(empty($txn_info)){
            http_response_code(400);
            echo json_encode(array("error" => "Transaction not initiated"));
            return;
        }

        // validate credit card
        $card = new BankCard;
        $account = new Account;
        $account->accountNo = $txn_info['accountNo'];
        $keys = $account->getKeysByAcctNo();
        $card->card_no = $reqbody['cardNo'];
        $card->txn_id = $txn_info['id'];
        $cardInfo = $card->getCardById();
        $cardCheck = !empty($cardInfo) ? $card->validateCardParam($cardInfo, $reqbody) : '';
        $mail = new MailingService;

        if(empty($cardInfo)){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Card number"));
            return;
        }else if(gettype($cardCheck) == 'string'){
            http_response_code(400);
            echo json_encode(array("error" => $cardCheck));
            return;
        }else if($txn_info['amount'] > $cardInfo['balance']){
            $txn->status = "failed";
            $txn->updateTxnStat();
            http_response_code(400);
            echo json_encode(array("error" => "Insufficient balance on card"));
            return;
        }else if($card->debitACard($txn_info['amount']) && $card->creditAccount($txn_info['amount'], $txn_info['accountNo']) && $card->insertIntoCardPayment()){
            $txn->status = "success";
            $txn->publicKey = $txn->encryptData($keys['publicKey']);
            $txn->updateTxnStat();
            $accInfo = $account->getAcctById();
            $txn->updateTxnMedium();
            if($txn->sendRespondsToWebhook($txn_info['webhook']) && $mail->transactionNotify($accInfo, $txn_info, 'Credit')){
                echo json_encode(array("message" => 'Transactions completed successfully'));
                return;
            }else{
                $card->creditCard($txn_info['amount']);
                $card->debitAccount($txn_info['amount'], $txn_info['accountNo']);
                $txn->status = "failed";
                $txn->updateTxnStat();
                $txn->sendRespondsToWebhook($txn_info['webhook']);
                http_response_code(500);
                echo json_encode(array("error" => "An error occured please try again"));
                return;
            }
        }else{
            $card->creditCard($txn_info['amount']);
            $card->debitAccount($txn_info['amount'], $txn_info['accountNo']);
            $txn->status = "failed";
            $txn->updateTxnStat();
            $txn->sendRespondsToWebhook($txn_info['webhook']);
            http_response_code(500);
            echo json_encode(array("error" => "An error occured please try again"));
            return;
        }
        
    });


    // refund payment
    Route::base("$baseName/webpayment_refund", function(){
        $middleware = new Middleware;
        if(!$middleware->verifySecreteKey()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("txn_id");
        $reqbody = json_decode(file_get_contents('php://input'), true);
        if(!$reqbody){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request data"));
            return;
        }

        // validate request body
        $checkValues = new Validator;
        $holdChecks = $checkValues->validateBody($reqbody, $Requiredbody);
        if(!empty($holdChecks)){
            http_response_code(400);
            echo json_encode($holdChecks);
            return;
        }

        $txn = new Transaction;
        $txn->txn_id = $reqbody['txn_id'];
        $txnInfo = $txn->getTxnById();

        if(empty($txnInfo)){
            http_response_code(400);
            echo json_encode(array("error" => 'No transaction with the ID = '.$reqbody['txn_id'].''));
            return;
        }else if($txnInfo['status'] != 'success' ){
            http_response_code(400);
            echo json_encode(array("error" => 'Cannot refund a transaction with status '.$txnInfo['status'].''));
            return;
        }else if($txnInfo['refunded'] == true){
            http_response_code(400);
            echo json_encode(array("error" => 'Transaction has been refunded already'));
            return;
        }else if($txnInfo['type'] == 'webpayment'){
            $acct1 = new Account;
            $acct1->accountNo = $txnInfo['accountNo'];
            $acct1_info = $acct1->getAcctById();

            if($acct1_info['balance'] < $txnInfo['amount']){
                http_response_code(400);
                echo json_encode(array("error" => 'Insuficient balance by to initiate refund'));
                return;
            }

            if($txnInfo['medium'] == 'card'){
                $card = new BankCard;
                $cardpayment = $card->getCardPaymentByTxnId($reqbody['txn_id']);
                $card->card_no = $cardpayment['card_no'] ?? 0 ;
                $mail = new MailingService;

                $acct1_info['balance'] = $acct1_info['balance'] - $txnInfo['amount'];
                if(
                    !empty($cardpayment) && 
                    $card->creditCard($txnInfo['amount']) && 
                    $txn->debitAccount($txnInfo['amount'], $txnInfo['accountNo']) &&
                    $mail->transactionNotify($acct1_info, $txnInfo, 'Refund & Debit')
                ){
                    $txn->refundTxn();
                    echo json_encode(array("message" => 'Transaction has been refunded successfully'));
                    return;
                }else{
                    http_response_code(500);
                    echo json_encode(array("error" => 'An error occured please try again'));
                    return;
                }
            }else if($txnInfo['medium'] == 'internal'){
                $txnTrans = $txn->getTxnTransByTxnId();
                $acct2 = new Account;
                $acct2->accountNo = $txnTrans['recipient_acctNo'] ?? 0;
                $acct2_info = $acct2->getAcctById();
                $mail = new MailingService;

                $acct1_info['balance'] = $acct1_info['balance'] - $txnInfo['amount'];
                $acct2_info['balance'] = $acct2_info['balance'] + $txnInfo['amount'];

                if(
                    !empty($txnTrans) &&
                    $txn->debitAccount($txnInfo['amount'], $txnInfo['accountNo']) &&
                    $txn->creditAccount($txnInfo['amount'], $acct2->accountNo) &&
                    $mail->transactionNotify($acct1_info, $txnInfo, 'Refund & Debit') &&
                    $mail->transactionNotify($acct2_info, $txnInfo, 'Refund & Credit')
                ){
                    $txn->refundTxn();
                    echo json_encode(array("message" => 'Transaction has been refunded successfully'));
                    return;
                }else{
                    http_response_code(500);
                    echo json_encode(array("error" => 'An error occured please try again'));
                    return;
                }

            }

        }

    });
?>