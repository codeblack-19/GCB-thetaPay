<?php

    class AccountKeys extends Account{
        public $id;
        public $appName;
        public $apiKey;
        public $publicKey;

        public $connect;

        function __construct(){
            require_once './db/config.php';
            
            $db_Object = new DbConnection;

            $this->connect = $db_Object->connect();
        }

        // save new keys
        public function saveKeys(){
            $today = date('Y/m/d H:i:s');
            $query = "INSERT INTO account_keys (appName, apiKey, publicKey, date, accountNo) VALUES ('$this->appName', '$this->apiKey', '$this->publicKey', '$today', '$this->accountNo')";
            $result = mysqli_query($this->connect, $query);

            // echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        // get keys by accountNo
        public function getAcctKeys(){
            $query = "Select * from account_keys Where accountNo = '$this->accountNo'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return $row;
        }

        // get keys by accountNo
        public function getAcctKeysById(){
            $query = "Select * from account_keys Where id = '$this->id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // get api_key
        public function getKeyByApiKey(){
            $query = "Select * from account_keys Where apiKey = '$this->apiKey'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // generate key
        public function generateApiKey(){
            $today = date('Y/m/d H:i:s');
            $randomText = $this->generateId(6);
            $body = array("accountNo" => $this->accountNo, "addon" => $randomText, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 2 years")));
            $this->apiKey = $this->encryptData(json_encode($body), $this->secreteKey);
        }

        // delete API key
        public function deleteApiKey(){
            $query = "DELETE FROM account_keys WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function generatePublicKey(){
            $today = date('Y/m/d H:i:s');
            $randomText = $this->generateId(5);
            $body = array("accountNo" => $this->accountNo, "addon" => $randomText, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 2 years")));
            $this->publicKey = $this->encryptData(json_encode($body), $this->secreteKey);
        }
    }

?>