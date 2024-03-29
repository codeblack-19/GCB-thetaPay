<?php 

    
    require_once './helpers/encryption.php';

    class User extends Cryptography{
        public $firstname;
        public $lastname;
        public $email;
        public $password;
        public $authToken;
        public $id;
        public $phone;
        public $createdAt;
        public $connect;
        public $bankNo = thetaBankNumber;


        function __construct(){
            require_once './db/config.php';
            $db_Object = new DbConnection;
            $this->connect = $db_Object->connect();
        }

        // get user by email
        function getuser_by_email(){
            $query = "Select * from users Where email = '$this->email'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // check if email exist
        public function checkEmail(){
            $query = "Select * from users Where email = '$this->email'";
            $result = mysqli_query($this->connect, $query);

            if($result->num_rows > 0){
                return true;
            }else{
                return false;
            }
        }

        // change password
        public function changepassword(){
            $query = "UPDATE users set password = '$this->password' WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);
            
            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function deleteAuthToken(){
            $query = "UPDATE users set authToken = null WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);
            
            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function updateAuthToken(){
            $query = "UPDATE users set authToken = '$this->authToken' WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function checkTokenExistence($token){
            $query = "Select * from users Where authToken = '$token'";
            $result = mysqli_query($this->connect, $query);

            if($result->num_rows > 0){
                return true;
            }else{
                return false;
            }
        }

        public function saveData(){
            $this->id = $this->generateId(12);
            $today = date('Y/m/d H:i:s');
            $query = "INSERT INTO users (id, firstname, lastname, password, email, phone, authToken, createdAt) VALUES ('$this->id', '$this->firstname', '$this->lastname','$this->password', '$this->email', '$this->phone', '$this->authToken','$today')";
            $result = mysqli_query($this->connect, $query);

            if($result === true){
                return $this->id;
            }else{
                return false;
            }
        }

        public function generateLoginToken($id){
            $today = date('Y/m/d H:i:s');
            $body = array("uid" => $id, "email" => $this->email, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 1 day")));
            return $this->encryptData(json_encode($body), thetaSecreteKey);
        }

        public function VerificationToken(){
            $today = date('Y/m/d H:i:s');
            $body = array("email" => $this->email, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 1 day")));
            return $this->encryptData(json_encode($body), thetaSecreteKey);
        }

        public function pemDestroy(){
            $query = "DELETE FROM users WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function getSettingDataById(){
            $query = "SELECT a.accountNo, a.businessName, a.secreteKey, u.firstname, u.lastname, u.phone, u.email FROM users u, accounts a WHERE a.user_id = u.id AND u.id = '$this->id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function getAdminSummary(){
            $query = "SELECT c.balance, (SELECT SUM(transactions.amount) FROM transactions) as txn_amt, (SELECT COUNT(*) from transactions) as txn_num, (SELECT COUNT(*) from users WHERE users.role = 'customer') as num_cus FROM cards c WHERE c.bankAcct_No = '$this->bankNo'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

    }

?>