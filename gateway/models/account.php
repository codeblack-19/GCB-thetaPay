<?php 

    require_once 'user.php';

    class Account extends User{
        public $balance;
        public $pinCode;
        public $businessName;
        public $accountNo;
        public $secreteKey;
        public $publicKey;
        public $status = "unverified";
        public $user_id;
        public $connect;

        function __construct(){
            require_once './db/config.php';
            
            $db_Object = new DbConnection;

            $this->connect = $db_Object->connect();
        }
        
        public function createAccount(){
            $this->user_id = $this->saveData();
            if(!$this->user_id){
                return false;
            }

            $query = "INSERT INTO accounts (pinCode, balance, businessName, secreteKey, publicKey, status, user_id) VALUES ('$this->pinCode', 0.0, '$this->businessName', '$this->secreteKey', '$this->publicKey','notverified', '$this->user_id')";
            $result = mysqli_query($this->connect, $query);

            if($result === true){
                return $this->id;
            }else{
                return false;
            }
        }

        public function verifyAccount(){
            $query = "UPDATE accounts set status = 'verified' WHERE user_id='$this->user_id'";
            $result = mysqli_query($this->connect, $query);
            
            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function getAccountbyUserId(){
            $query = "Select balance, status, accountNo, businessName from accounts Where user_id = '$this->user_id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }
    }


?>