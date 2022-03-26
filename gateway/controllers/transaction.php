<?php 

    require_once './controllers/RouteConfig/config.php';
    require_once './helpers/validator.php';
    require_once './models/transaction.php';
    require_once './controllers/viewController.php';

    
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
            $link = 'http://localhost/GCB-thetaPay/gateway/transactions/paymentpage?token='.$txn->txn_token.'';
            echo json_encode(array("link"=> "$link", "message"=>"Payment initiated successfully"));
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

        ViewController::CreateView("transactions/paymentpage");
        return;
    });

?>