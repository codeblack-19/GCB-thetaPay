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
        public $updatedAt;
        public $deletedAt;
        public $connect;


        function __construct(){
            require_once './db/config.php';
            $db_Object = new DbConnection;
            $this->connect = $db_Object->connect();
        }

        // get user by email
        function getuser_by_email(){
            $query = "Select id, email, firstname, lastname, createdAT, updatedAT, authToken,  from users Where email = '$this->email'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function checkEmail(){
            $query = "Select * from users Where email = '$this->email'";
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
            $query = "INSERT INTO users (id, firstname, lastname, password, email, phone, authToken, createdAt, updatedAt) VALUES ('$this->id', '$this->firstname', '$this->lastname','$this->password', '$this->email', '$this->phone', '$this->authToken','$today', '$today')";
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
            return $this->encryptData(json_encode($body));
        }

        public function VerificationToken(){
            $today = date('Y/m/d H:i:s');
            $body = array("email" => $this->email, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 1 day")));
            return $this->encryptData(json_encode($body));
        }

        public function pemDestroy(){
            $query = "DELETE FROM users WHERE id='$this->id'";
            $result = mysqli_query($this->connect, $query);

            
            echo mysqli_error($this->connect);

            if($result === true){
                return $this->id;
            }else{
                return false;
            }
        }
    }

?>