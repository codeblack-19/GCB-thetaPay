<?php 

    require_once './controllers/RouteConfig/config.php';
    
    $baseName = '/admin';

    Route::base("$baseName", function(){
        session_start();

        if(!isset($_SESSION['admin_token'])){
            header('location: /GCB-thetaPay/gateway/admin/login');
            return;
        }

        ViewController::CreateView("admin/dashboard");
    });

    Route::base("$baseName/login", function(){
        session_start();

        if(isset($_SESSION['admin_token'])){
            header('location: /GCB-thetaPay/gateway/admin');
            return;
        }

        ViewController::CreateView("admin/login");
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

        $user = new User;
        $summary = $user->getAdminSummary();

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
        $txn = $txn->getTxnForAdmin();

        echo json_encode($txn);
        return;
    });
?>