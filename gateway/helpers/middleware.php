<?php 
    require_once './models/accountKeys.php';

    class Middleware extends AccountKeys{

        public function verifyCSToken(){
            header("Content-Type: application/json; charset=UTF-8");
            if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1] ?? "";

                // first find token in table before decoding
                $_token = $this->checkTokenExistence($token);
                if(!$_token){
                    http_response_code(401);
                    echo json_encode(array("error" => "Invalid Authorization token"));
                    return false;
                }

                $decode = json_decode($this->decryptData($token, thetaSecreteKey), true);

                if(isset($decode['uid'])){
                    if($decode['edt'] <= date('Y/m/d H:i:s')){
                        http_response_code(401);
                        echo json_encode(array("error" => "Authorization token has expired"));
                        return false;
                    }else{
                        $_GET['uid'] = $decode['uid'];
                        return true;
                    }
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

        public function verifySecreteKey(){
            header("Content-Type: application/json; charset=UTF-8");
            if(isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION'])){
                $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];

                if($token){
                    $this->apiKey = $token;
                    $keyInfo = $this->getKeyByApiKey();
                    
                    // find secrete key
                    if(!empty($keyInfo)){
                        $this->accountNo = $keyInfo['accountNo'];
                        $S_Key = $this->getKeysByAcctNo();

                        if($this->checkSignatureDate($token, $S_Key['secreteKey'])){
                            http_response_code(401);
                            echo json_encode(array("error" => "API key has expired please generate a new key"));
                            return false;
                        }

                        $keyInfo['secreteKey'] = $S_Key['secreteKey'];
                        $_GET['keyInfo'] = $keyInfo;
                        return true;
                    }else{
                        http_response_code(401);
                        echo json_encode(array("error" => "Invalid Authorization token"));
                        return false;
                    }
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