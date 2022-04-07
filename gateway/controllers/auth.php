<?php 
    
    require_once './controllers/RouteConfig/config.php';
    require_once './models/account.php';
    require_once './helpers/validator.php';
    require_once './services/SendMail.php';
    require_once './controllers/viewController.php';
    require_once './helpers/middleware.php';

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
        $account->publicKey = $account->generateId(32);

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
            $link = 'http://localhost/GCB-thetaPay/gateway/auth/verifyAccount?email='.$account->email.'&signature='.$account->authToken.'';
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
        $requiredquery = array("email", "signature");
        $queryStrings = array();
        parse_str($_SERVER['QUERY_STRING'], $queryStrings);

        // validate req queries
        $validate = new Validator;
        $holdValues = $validate->validateQueryStrings($queryStrings, $requiredquery);
        if(!empty($holdValues)){
            http_response_code(400);
            ViewController::CreateViewWithParams('auth/verifyAccount?error='.$holdValues[0]['error'].'');
            return;
        }

        $user = new User;
        $account = new Account;
        $user->email = $_GET['email'];
        $userInfo = $user->getuser_by_email();
        
        if(empty($userInfo)){
            ViewController::CreateViewWithParams('auth/verifyAccount?error=Invalid Email address or Email does not exist');
            return;
        }else if($userInfo['authToken'] != $_GET['signature']){
            ViewController::CreateViewWithParams('auth/verifyAccount?error=Invalid Signature or token');
            return;
        }

        $user->id = $userInfo['id'];
        $account->user_id = $userInfo['id'];
        $accountInfo = $account->getAccountbyUserId();

        if($accountInfo['status'] === 'verified'){
            ViewController::CreateViewWithParams('auth/verifyAccount?error=Account is already verified');
            return;
        }else if($account->checkSignatureDate($_GET['signature'])){
            ViewController::CreateViewWithParams('auth/verifyAccount?error=Verification signture has expired');
            return;
        }else if($user->deleteAuthToken() && $account->verifyAccount()){
            ViewController::CreateViewWithParams('auth/verifyAccount?message=success');
            return;
        }
    });

    // request for verification token
    Route::base("$baseName/getVerifyToken", function(){
        header("Content-Type: application/json; charset=UTF-8");

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("email");
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

        $user = new User;
        $user->email = $reqbody["email"];
        $user->authToken = $user->VerificationToken();
        $userInfo = $user->getuser_by_email();
        $user->id = $userInfo['id'];

        if(filter_var($user->email, FILTER_SANITIZE_EMAIL) === false){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Email address"));
            return;
        }else if(empty($userInfo)){
            http_response_code(400);
            echo json_encode(array("error" => "$user->email does not exist in database"));
            return;
        }else if($user->updateAuthToken()){
            $signUpmail = new MailingService;
            $link = 'http://localhost/GCB-thetaPay/gateway/auth/verifyAccount?email='.$user->email.'&signature='.$user->authToken.'';
            if($signUpmail->sendMail($user->email, $link)){
                echo json_encode(array("message" => "Verification link has been sent to $user->email"));
                return;
            }else{
                $user->deleteAuthToken();
                http_response_code(500);
                echo json_encode(array('error'=> 'An error occured please try again'));
                return;
            }
        }

    });

    // reset password
    Route::base("$baseName/resetpassword", function(){
        header("Content-Type: application/json; charset=UTF-8");

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        // expected body
        $Requiredbody = array("email");
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

        $user = new User;
        $user->email = $reqbody["email"];
        $user->authToken = $user->VerificationToken();
        $userInfo = $user->getuser_by_email();
        $user->id = $userInfo['id'];

        if(filter_var($user->email, FILTER_SANITIZE_EMAIL) === false){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Email address"));
            return;
        }else if(empty($userInfo)){
            http_response_code(400);
            echo json_encode(array("error" => "$user->email does not exist in database"));
            return;
        }else if($user->updateAuthToken()){
            $mail = new MailingService;
            $link = 'http://localhost/GCB-thetaPay/gateway/auth/changepassword?email='.$user->email.'&signature='.$user->authToken.'';
            if($mail->sendResetPasswordMail($user->email, $link)){
                echo json_encode(array("message" => "Reset password link has been sent to $user->email"));
                return;
            }else{
                $user->deleteAuthToken();
                http_response_code(500);
                echo json_encode(array('error'=> 'An error occured please try again'));
                return;
            }
        }

    });

    // change password page
    Route::base("$baseName/changepassword", function(){
        
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            // required query strings
            $requiredquery = array("email", "signature");
            $queryStrings = array();
            parse_str($_SERVER['QUERY_STRING'], $queryStrings);

            // validate req queries
            $validate = new Validator;
            $holdValues = $validate->validateQueryStrings($queryStrings, $requiredquery);
            if(!empty($holdValues)){
                http_response_code(400);
                ViewController::CreateViewWithParams('auth/verifyAccount?error='.$holdValues[0]['error'].'');
                return;
            }

            $user = new User;
            $user->email = $_GET['email'];
            $userInfo = $user->getuser_by_email();

            if(filter_var($user->email, FILTER_SANITIZE_EMAIL) === false){
                ViewController::CreateViewWithParams("auth/changepassword?error=Invalid Email address");
                return;
            }else if(empty($userInfo)){
                ViewController::CreateViewWithParams("auth/changepassword?error=$user->email does not exist in database");
                return;
            }else if($userInfo['authToken'] != $_GET['signature']){
                ViewController::CreateViewWithParams('auth/changepassword?error=Invalid signature');
                return;
            }else if($user->checkSignatureDate($_GET['signature'])){
                ViewController::CreateViewWithParams('auth/changepassword?error=Signture has expired');
                return;
            }else{
                ViewController::CreateViewWithParams('auth/changepassword?message=success');
                return;
            }

        }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
            header("Content-Type: application/json; charset=UTF-8");

            // expected body
            $Requiredbody = array("email", "password", "signature");
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

            $user = new User;
            $user->email = $reqbody["email"];
            $user->password = password_hash($reqbody["password"], PASSWORD_BCRYPT);
            $userInfo = $user->getuser_by_email();
            $user->id = $userInfo['id'];

            if(filter_var($user->email, FILTER_SANITIZE_EMAIL) === false){
                http_response_code(400);
                echo json_encode(array("error" => "Invalid Email address"));
                return;
            }else if(empty($userInfo)){
                http_response_code(400);
                echo json_encode(array("error" => "$user->email does not exist in database"));
                return;
            }else if($userInfo['authToken'] != $reqbody['signature']){
                http_response_code(400);
                echo json_encode(array("error" => "Invalid signature"));
                return;
            }else if($user->checkSignatureDate($reqbody['signature'])){
                http_response_code(400);
                echo json_encode(array("error" => "Signture has expired"));
                return;
            }else if($user->deleteAuthToken() && $user->changepassword()){
                echo json_encode(array("message" => "Password changed successfully"));
                return;
            }


        }else{
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }
    });

    // login customer
    Route::base("$baseName/login_ct", function(){
        header("Content-Type: application/json; charset=UTF-8");

        // expected body
        $Requiredbody = array("email", "password");
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

        $user = new User;
        $user->email = $reqbody['email'];
        $userInfo = $user->getuser_by_email();

        if(empty($userInfo)){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Email address or Email does not exist"));
            return;
        }else if(!password_verify($reqbody['password'], $userInfo['password'])){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid Password"));
            return;
        }

        $account = new Account;
        $account->user_id = $userInfo['id'];
        $user->id = $userInfo['id'];
        $user->authToken = $user->generateLoginToken($userInfo['id']);
        $userInfo['account_info'] = $account->getAccountbyUserId();
        $userInfo['access_token'] = $user->authToken;
        unset($userInfo['password']);
        unset($userInfo['authToken']);

        if($userInfo['account_info']['status'] != 'verified'){
            http_response_code(401);
            echo json_encode(array("error" => "This account is not verified"));
            return;
        }else if($user->updateAuthToken()){
            session_start();
            $_SESSION["user_token"] = $userInfo['access_token'];
            echo json_encode(array("message" => "succcess", "token" => $userInfo['access_token'] ));
            return;
        }
    });

    // logout
    Route::base("$baseName/logout",function(){
        
        $middleware = new Middleware;
        if(!$middleware->verifyCSToken()){
            session_start();
            unset($_SESSION['user_token']);
            return;
        }

        header("Content-Type: application/json; charset=UTF-8");
        if($_SERVER['REQUEST_METHOD'] != 'PUT'){
            http_response_code(400);
            echo json_encode(array("error" => "Invalid request method"));
            return;
        }

        $user = new User;
        $user->id = $_GET['uid'];

        if($user->deleteAuthToken()){
            session_start();
            unset($_SESSION['user_token']);
            echo json_encode(array("message" => "logged out successfully"));
            return;
        }

    });

?>