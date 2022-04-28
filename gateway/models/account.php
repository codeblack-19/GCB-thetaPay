<?php 

    require_once 'user.php';

    class Account extends User{
        public $balance;
        public $pinCode;
        public $businessName;
        public $accountNo;
        public $secreteKey;
        public $status = "notverified";
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

            $query = "INSERT INTO accounts (pinCode, balance, businessName, secreteKey, status, user_id) VALUES ('$this->pinCode', 0.0, '$this->businessName', '$this->secreteKey', 'notverified', '$this->user_id')";
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

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function getAcctById(){
            $query = "Select users.firstname, users.lastname, users.email, accounts.balance, accounts.pinCode, accounts.status, accounts.accountNo, accounts.businessName from accounts, users Where accounts.user_id = users.id AND accounts.accountNo = '$this->accountNo'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function changePinCode(){
            $query = "UPDATE accounts set pinCode = '$this->pinCode' WHERE user_id='$this->user_id'";
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

        public function findbySecreteKey(){
            $query = "Select balance, status, accountNo, businessName from accounts Where secreteKey = '$this->secreteKey'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function getSecretekey(){
            $query = "Select secreteKey, accountNo from accounts Where user_id = '$this->user_id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function getKeysByAcctNo(){
            $query = "Select secreteKey from accounts Where accountNo = '$this->accountNo'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function getAccountSummary(){
            $query = "SELECT (SELECT COUNT(*) from transactions WHERE transactions.accountNo = a.accountNo) as Num_txn, COALESCE(SUM(t.amount), 0.00) as txn_amount, COALESCE((a.balance), 0.00) as balance FROM transactions as t, accounts as a, users as u WHERE a.accountNo = t.accountNo AND a.accountNo = '$this->accountNo';";
            
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function changebasicInfo(){
            $query = "UPDATE users join accounts on users.id = accounts.user_id SET users.lastname='$this->lastname', users.firstname='$this->firstname', users.phone='$this->phone', accounts.businessName = '$this->businessName' WHERE users.id = '$this->user_id'";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }
    }


?>