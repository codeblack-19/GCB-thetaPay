<?php 

    require_once './controllers/RouteConfig/config.php';
    require_once './helpers/validator.php';
    require_once './models/transaction.php';

    
    $baseName = '/transaction';

    // initiate web payment
    Route::base("$baseName/initiate-payment", function(){
        $middleware = new Middleware;
        if(!$middleware->verifyCSToken()){
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
        $txn->description = $reqbody['description'];
        $txn->amount = $reqbody['amount'];
        $txn->currency = $reqbody['currency'];
        $txn->webhook = $reqbody['webhook'];
        $txn->status = 'pending';
        $txn->type = 'webpayment';

        

    });


?>