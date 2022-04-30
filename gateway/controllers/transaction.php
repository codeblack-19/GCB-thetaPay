<?php 

    require_once './controllers/RouteConfig/config.php';
    require_once './helpers/validator.php';
    require_once './models/transaction.php';
    require_once './controllers/viewController.php';
    require_once './models/bankcard.php';
    
    $baseName = '/transactions';

    // get transactions
    Route::base("$baseName", function(){
        $middleware = new Middleware;
        if(!$middleware->verifySecreteKey()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $txn = new Transaction;
        $txn->accountNo = $_GET['keyInfo']['accountNo'];
        $txn = $txn->getTxnByAcctNo();

        echo json_encode($txn);
        return;
    });

    // get a single transaction
    Route::base("$baseName/one", function(){
        $middleware = new Middleware;
        if(!$middleware->verifySecreteKey()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // required query strings
        $requiredquery = array("txnId");
        $queryStrings = array();
        parse_str($_SERVER['QUERY_STRING'], $queryStrings);

        // validate req queries
        $validate = new Validator;
        $holdValues = $validate->validateQueryStrings($queryStrings, $requiredquery);
        if(!empty($holdValues)){
            http_response_code(400);
            echo json_encode($holdValues[0]);
            return;
        }

        $txn = new Transaction;
        $txn->accountNo = $_GET['keyInfo']['accountNo'];
        $txn->txn_id = $_GET['txnId'];
        $txn = $txn->getTxnByAcctNoTxnId();

        echo json_encode($txn);
        return;
    });

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
        $txn->accountNo = $_GET['keyInfo']['accountNo'];
        $txn->currency = $reqbody['currency'];
        $txn->description = $reqbody['description'];
        $txn->webhook = $reqbody['webhook'];
        $key = $txn->getKeysByAcctNo();
        $txn->secreteKey = $key['secreteKey'];
        $txn->status = 'initiated';
        $txn->type = 'webpayment';
        $txn->convertAmt($reqbody['amount']);
        $txn->txn_id = $txn->generateId(10);
        $txn->txn_token = $txn->getTxnToken();
        
        if($txn->saveInitPaymentTxn()){
            $today = date('Y/m/d H:i:s');
            $link = 'http://localhost/GCB-thetaPay/gateway/transactions/paymentpage?signature='.$txn->txn_token.'&_id='.$_GET['keyInfo']['id'].'';
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
        $requiredquery = array("signature", "_id");
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

        $acctkeys = new AccountKeys;
        $acctkeys->id = $_GET['_id'];
        $keys = $acctkeys->getAcctKeysById();

        $txn = new Transaction;
        $txn->txn_token = $_GET['signature'];
        $txn->accountNo = $keys['accountNo'];
        $sKey = $txn->getKeysByAcctNo();
        $txn->secreteKey = $sKey['secreteKey'];
        $txn->status = 'pending';
        $txnArr = $txn->decodeTxnToken();
        $txn->txn_id = $txnArr['txn_id'] ?? '';

        if(!$txnArr){
            ViewController::CreateViewWithParams("transactions/paymentpage?error=Invalid signature");
            return;
        }else if($txn->checkSignatureDate($_GET['signature'], $sKey['secreteKey'])){
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
        $Requiredbody = array("accountNo", "pinCode", "signature", "_id");
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

        $acctkeys = new AccountKeys;
        $acctkeys->id = $reqbody['_id'];
        $keys = $acctkeys->getAcctKeysById();
        
        $txn = new Transaction;
        $txn->txn_token = $reqbody['signature'];
        $txn->accountNo = $keys['accountNo'];
        $sKey = $txn->getKeysByAcctNo();
        $txn->secreteKey = $sKey['secreteKey'];
        $txnArr = $txn->decodeTxnToken();
        $txn->medium = 'internal';
        $txn->recipient_acctNo = $reqbody['accountNo'];
        $txn->txn_id = $txnArr['txn_id'] ?? '';
        $txn_info = $txn->getTxnById();

        $chargeAmt = $txn_info['amount'] - ($txn_info['amount'] * chargePercent);

        // validate token first
        if(!$txnArr){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid signature"));
            return;
        }else if($txn->checkSignatureDate($reqbody['signature'], $sKey['secreteKey'])){
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
        $bank = new BankCard;

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
        }else if(
            $txn->creditAccount($chargeAmt, $txn_info['accountNo']) && 
            $txn->debitAccount($txn_info['amount'], $reqbody['accountNo']) && 
            $bank->creditMainAcct($txn_info['amount']) &&
            $txn->insertIntoTransfer()
        ){
            $account1 = new AccountKeys;
            $account1->accountNo = $txn_info['accountNo'];
            $account1Info = $account1->getAcctById();
            $accInfo = $account->getAcctById();
            $txn->status = "success";
            $txn->updateTxnStat();
            $txn->updateTxnMedium();

            if(
                $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']) && 
                $mail->transactionNotify($accInfo, $txn_info, 'Debit', $txn_info['amount']) &&
                $mail->transactionNotify($account1Info, $txn_info, 'Credit', $chargeAmt)
            ){
                echo json_encode(array("message" => "Transactions completed successfully"));
                return;
            }else{
                $txn->debitAccount($chargeAmt, $txn_info['accountNo']);
                $txn->creditAccount($txn_info['amount'], $reqbody['accountNo']);
                $bank->debitMainAcct($txn_info['amount']);
                $txn->status = "failed";
                $txn->updateTxnStat();
                $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']);
                http_response_code(500);
                echo json_encode(array("error" => "An error occured please try again"));
                return;
            }
        }else{
            $txn->debitAccount($chargeAmt, $txn_info['accountNo']);
            $txn->creditAccount($txn_info['amount'], $reqbody['accountNo']);
            $bank->debitMainAcct($txn_info['amount']);
            $txn->status = "failed";
            $txn->updateTxnStat();
            $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']);
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
        $Requiredbody = array("name", "cardNo", "cvv", "expiry_mm", "expiry_yy", "signature", "_id");
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

        $acctkeys = new AccountKeys;
        $acctkeys->id = $reqbody['_id'];
        $keys = $acctkeys->getAcctKeysById();

        $txn = new Transaction;
        $txn->txn_token = $reqbody['signature'];
        $txn->accountNo = $keys['accountNo'];
        $sKey = $txn->getKeysByAcctNo();
        $txn->secreteKey = $sKey['secreteKey'];
        $txnArr = $txn->decodeTxnToken();
        $txn->medium = 'card';
        $txn->cardNo = $reqbody['cardNo'];
        $txn->txn_id = $txnArr['txn_id'] ?? '';
        $txn_info = $txn->getTxnById();

        $chargeAmt = $txn_info['amount'] - ($txn_info['amount'] * chargePercent);

        // validate token first
        if(!$txnArr){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid signature"));
            return;
        }else if($txn->checkSignatureDate($reqbody['signature'], $sKey['secreteKey'])){
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
        }else if(
            $card->debitACard($txn_info['amount']) && 
            $card->creditAccount($chargeAmt, $txn_info['accountNo']) && 
            $card->creditMainAcct($txn_info['amount']) &&
            $card->insertIntoCardPayment()
        ){
            $txn->status = "success";
            $txn->updateTxnStat();
            $accInfo = $account->getAcctById();
            $txn->updateTxnMedium();
            if(
                $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']) && 
                $mail->transactionNotify($accInfo, $txn_info, 'Credit', $chargeAmt)
            ){
                echo json_encode(array("message" => 'Transactions completed successfully'));
                return;
            }else{
                $card->creditCard($txn_info['amount']);
                $card->debitAccount($chargeAmt, $txn_info['accountNo']);
                $card->debitMainAcct($txn_info['amount']);
                $txn->status = "failed";
                $txn->updateTxnStat();
                $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']);
                http_response_code(500);
                echo json_encode(array("error" => "An error occured please try again"));
                return;
            }
        }else{
            $card->creditCard($txn_info['amount']);
            $card->debitAccount($chargeAmt, $txn_info['accountNo']);
            $card->debitMainAcct($txn_info['amount']);
            $txn->status = "failed";
            $txn->updateTxnStat();
            $txn->sendRespondsToWebhook($txn_info['webhook'], $keys['publicKey']);
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

        $chargeAmt = $txnInfo['amount'] - ($txnInfo['amount'] * chargePercent);

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
                    $card->creditCard($chargeAmt) && 
                    $txn->debitAccount($chargeAmt, $txnInfo['accountNo']) &&
                    $card->debitMainAcct($chargeAmt) &&
                    $mail->transactionNotify($acct1_info, $txnInfo, 'Refund & Debit', $chargeAmt)
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
                    $txn->debitAccount($chargeAmt, $txnInfo['accountNo']) &&
                    $txn->creditAccount($chargeAmt, $acct2->accountNo) &&
                    $mail->transactionNotify($acct1_info, $txnInfo, 'Refund & Debit', $chargeAmt) &&
                    $mail->transactionNotify($acct2_info, $txnInfo, 'Refund & Credit', $chargeAmt)
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

    // cron api
    Route::base("$baseName/8937583481436", function(){
        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $txn = new Transaction;
        $allTxn = $txn->getAllTxn();

        if(!empty($allTxn)){
            foreach($allTxn as $value){
                if($value['status'] == 'pending' || $value['status'] == 'initiated'){
                    $txn->txn_id = $value['id'];
                    $txn->accountNo = $value['accountNo'];
                    $sKey = $txn->getKeysByAcctNo();
                    $txn->secreteKey = $sKey['secreteKey'];

                    if($txn->checkSignatureDate($value['token'], $txn->secreteKey)){
                        $txn->status = 'failed';
                        $txn->updateTxnStat();
                    }
                }
            }
        }

        return;
    });
?>