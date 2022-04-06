<?php 
    
    require_once './controllers/RouteConfig/config.php';
    require_once './controllers/viewController.php';
    require_once './helpers/middleware.php';

    $baseName = '/client';

    Route::base("$baseName", function(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }
        ViewController::CreateView("client/home");
    });

    Route::base("$baseName/login", function(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }
        ViewController::CreateView("client/login");
    });

    Route::base("$baseName/signup", function(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }
        ViewController::CreateView("client/signup");
    });

    Route::base("$baseName/dashboard", function(){
        session_start();

        if(!isset($_SESSION['user_token'])){
            header('location: /GCB-thetaPay/gateway/client');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        ViewController::CreateView("client/dashboard");
    });

    Route::base("$baseName/getSummary", function(){
        $middleware = new Middleware;
        if(!$middleware->verifyCSToken()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $account = new Account;
        $account->user_id = $_GET['uid'];
        $accountInfo = $account->getAccountbyUserId();
        $account->accountNo = $accountInfo['accountNo'];
        $summary = $account->getAccountSummary();

        echo json_encode($summary);
        return;
    });

    Route::base("$baseName/getTxns", function(){
        $middleware = new Middleware;
        if(!$middleware->verifyCSToken()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $txn = new Transaction;
        $txn->user_id = $_GET['uid'];
        $accountInfo = $txn->getAccountbyUserId();
        $txn->accountNo = $accountInfo['accountNo'];
        $txn = $txn->getTxnByAcctNo();

        echo json_encode($txn);
        return;
    });

    Route::base("$baseName/settings", function(){
        session_start();

        if(!isset($_SESSION['user_token'])){
            header('location: /GCB-thetaPay/gateway/client');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $user = new User;
        $decodetoken = json_decode($user->decryptData($_SESSION['user_token']), true);
        $user->id = $decodetoken['uid'];
        $userInfo = $user->getSettingDataById();
        $userInfo['secreteKey'] = $user->encryptData($userInfo['secreteKey']);
        $userInfo['publicKey'] = $user->encryptData($userInfo['publicKey']);
        $_GET['acct_data'] = $userInfo;

        ViewController::CreateView("client/settings");
    });

    Route::base("$baseName/topup", function(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            session_start();
            if(!isset($_SESSION['user_token'])){
                header('location: /GCB-thetaPay/gateway/client');
                return;
            }

            ViewController::CreateView("client/topup");
        }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $middleware = new Middleware;
            if(!$middleware->verifyCSToken()){
                return;
            }

            header("Content-Type: application/json; charset=UTF-8");

            // expected body
            $Requiredbody = array("name", "cardNo", "cvv", "expiry_mm", "expiry_yy", "amount");
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
            $txn->user_id = $_GET['uid'];
            $accountInfo = $txn->getAccountbyUserId();
            $txn->accountNo = $accountInfo['accountNo'];
            $txn->currency = "GHS";
            $txn->type = "deposit";
            $txn->medium = "card";
            $txn->description = "wallet Top up";
            $txn->convertAmt($reqbody['amount']);
            $id = $txn->generateId(10);
            $txn->txn_id = $id;

            $card = new BankCard;
            $card->card_no = $reqbody['cardNo'];
            $card->txn_id = $id;
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
            }else if($reqbody['amount'] > $cardInfo['balance']){
                http_response_code(400);
                echo json_encode(array("error" => "Insufficient balance on card"));
                return;
            }else if($card->debitACard($reqbody['amount']) && $card->creditAccount($reqbody['amount'], $accountInfo["accountNo"])){
                $txn->status = "success";
                $txn->saveTopUpTxn();
                $txn_info = $txn->getTxnById();
                $accInfo = $txn->getAcctById();
                $card->insertIntoCardPayment();

                if($mail->transactionNotify($accInfo, $txn_info, 'Credit')){
                    echo json_encode(array("message" => 'Top up completed successfully'));
                    return;
                }else{
                    $card->creditCard($txn_info['amount']);
                    $card->debitAccount($txn_info['amount'], $txn_info['accountNo']);
                    $txn->status = "failed";
                    $txn->updateTxnStat();
                    http_response_code(500);
                    echo json_encode(array("error" => "An error occured please try again"));
                    return;
                }
            }else{
                $card->creditCard($reqbody['amount']);
                $card->debitAccount($reqbody['amount'], $accountInfo["accountNo"]);
                $txn->status = "failed";
                $txn->updateTxnStat();
                http_response_code(500);
                echo json_encode(array("error" => "An error occured please try again"));
                return;
            }

        }else{
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }
    });
?>