<?php 
    require_once './models/user.php';

    class Middleware extends User{

        public function verifyCSToken(){
            header("Content-Type: application/json; charset=UTF-8");
            if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];
                $decode = json_decode($this->decryptData($token), true);

                if(isset($decode['uid'])){
                    $_GET['uid'] = $decode['uid'];
                    return true;
                }else{
                    http_response_code(401);
                    echo json_encode(array("error" => "Invalid Authorization token"));
                    return false;
                }
                
            }else{
                http_response_code(403);
                echo json_encode(array("error" => "Authorization token needed for this operation"));
                return false;
            }
        }
    }

?>