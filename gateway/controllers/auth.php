<?php 
    
    require_once './controllers/RouteConfig/config.php';
    require_once './models/account.php';
    require_once './helpers/validator.php';
    require_once './services/SendMail.php';
    require_once './controllers/viewController.php';
    $baseName = '/auth';

    // signup customer
    Route::base("$baseName/signup", function(){
        header("Content-Type: application/json; charset=UTF-8");

        // expected body
        $Requiredbody = array("firstname", "lastname", "email", "password",  "pinCode");
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

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
        $account->firstname = $reqbody["firstname"];
        $account->lastname = $reqbody["lastname"];
        $account->email = $reqbody["email"];
        $account->phone = $reqbody["phone"];
        $account->password = password_hash($reqbody["password"], PASSWORD_BCRYPT);
        $account->businessName = $reqbody["businessName"];
        $account->pinCode = password_hash($reqbody["pinCode"], PASSWORD_BCRYPT);
        $account->authToken = $account->VerificationToken();
        $account->secreteKey =  $account->generateId(32);

        $signUpmail = new MailingService;

        // validate email type
        if(filter_var($account->email, FILTER_SANITIZE_EMAIL) === false){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Email address"));
            return;
        }else if($account->checkEmail()){
            http_response_code(400);
            echo json_encode(array("error" => "Email is already Taken"));
            return;
        }else if($account->createAccount()){
            $link = 'http://localhost'.$_SERVER["REQUEST_URI"].'/auth/verifyAccount?email='.$account->email.'&signature='.$account->authToken.'';
            if($signUpmail->sendMail($account->email, $link)){
                echo json_encode(array("user_id" => $account->id));
                return;
            }else{
                $account->pemDestroy();
                http_response_code(500);
                echo json_encode(array('error'=> 'SignUp failed'));
                return;
            }
        }else{
            http_response_code(500);
            echo json_encode(array('error'=> 'SignUp failed'));
            return;
        }
    });

    // verify account
    Route::base("$baseName/verifyAccount", function(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // required query strings
        // $requiredquery = array("email", "signature");
        // $queryStrings = array();
        // parse_str($_SERVER['QUERY_STRING'], $queryStrings);

        // // validate req queries
        // $validate = new Validator;
        // $holdValues = $validate->validateBody($queryStrings, $requiredquery);
        // if(!empty($holdValues)){
        //     http_response_code(400);
        //     echo json_encode($holdValues);
        //     return;
        // }


        ViewController::CreateView('auth/verifyAccount');

    });


    // request for verification token
    // get change password page
    // change password

?>