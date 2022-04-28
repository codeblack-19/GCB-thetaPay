<?php 

    require_once './controllers/RouteConfig/config.php';
    require_once './helpers/middleware.php';
    require_once './models/accountKeys.php';

    $baseName = '/accounts';

    // get secrete key
    Route::base("$baseName/secreteKey", function(){
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
        $result = $account->getSecretekey();

        if(!empty($result)){
            echo json_encode(array("secretekey" => $account->encryptData($result['secreteKey'], thetaSecreteKey)));
            return;
        }else{
            http_response_code(500);
            echo json_encode(array('error'=> 'An error occured please try again'));
            return;
        }

    });

    // change pin code
    Route::base("$baseName/updatepinCode", function(){
        $middleware = new Middleware;
        if(!$middleware->verifyCSToken()){
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'PUT'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // required body
        $Requiredbody = array("pinCode");
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

        $account = new Account;
        $account->user_id = $_GET['uid'];
        $account->pinCode = password_hash($reqbody["pinCode"], PASSWORD_BCRYPT);

        if($account->changePinCode()){
            echo json_encode(array('message'=> 'Pin Code updated successfully'));
            return;
        }else{
            http_response_code(400);
            echo json_encode(array("error" => "An error occured please try again"));
            return;
        }
    });

    // create new api_keys
    Route::base("$baseName/newKeys", function(){
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

        // required body
        $Requiredbody = array("appName");
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

        $accountKey = new AccountKeys;
        $accountKey->user_id = $_GET['uid'];
        $result = $accountKey->getSecretekey();
        $accountKey->secreteKey = $result['secreteKey'];
        $accountKey->accountNo = $result['accountNo'];
        $accountKey->appName = $reqbody['appName'];
        $accountKey->generateApiKey();
        $accountKey->generatePublicKey();

        if($accountKey->saveKeys()){
            echo json_encode(array('message'=> 'Account Keys Created successfully'));
            return;
        }else{
            http_response_code(400);
            echo json_encode(array("error" => "An error occured please try again"));
            return;
        }

    });

    // get all keys
    Route::base("$baseName/getKeys", function(){
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

        $accountKey = new AccountKeys;
        $accountKey->user_id = $_GET['uid'];
        $result = $accountKey->getAccountbyUserId();
        $accountKey->accountNo = $result['accountNo'];
        $keys = $accountKey->getAcctKeys();

        echo json_encode($keys);
        return;
    });
?>