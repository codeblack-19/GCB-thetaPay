<?php 

    require_once './models/account.php';

    class Transaction extends Account {
        public $txn_id;
        public $status;
        public $amount;
        public $description;
        public $type;
        public $webhook;
        public $txn_token;
        public $currency;
        public $medium;
        public $accountNo;
        public $recipient_acctNo;
        public $createdAt;
        public $updatedAt;
        public $cardNo;
        public $connect;

        function __construct(){
            require_once './db/config.php';
            $db_Object = new DbConnection;
            $this->connect = $db_Object->connect();
        }


        public function saveInitPaymentTxn(){
            $today = date('Y/m/d H:i:s');
            $query = "INSERT INTO transactions (id, status, amount, description, type, webhook, token, currency, accountNo,createdAt, updatedAt) VALUES ('$this->txn_id', '$this->status', $this->amount,'$this->description', '$this->type', '$this->webhook', '$this->txn_token', '$this->currency', $this->accountNo,'$today', '$today')";
            $result = mysqli_query($this->connect, $query);


            if($result){
                return true;
            }else{
                return false;
            }
        }

        // get txn by id
        public function getTxnById(){
            $query = "Select * from transactions Where id = '$this->txn_id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // get txn by accountNo
        public function getTxnByAcctNo(){
            $query = "Select * from transactions Where accountNo = '$this->accountNo'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return $row;
        }

        // insert into transfer
        public function insertIntoTransfer(){
            $query = "INSERT INTO transfers (txnId, recipient_acctNo) VALUES ('$this->txn_id', '$this->recipient_acctNo')";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        // update txn status
        public function updateTxnStat(){
            $query = "UPDATE transactions set status = '$this->status' WHERE id='$this->txn_id'";
            $result = mysqli_query($this->connect, $query);

            if($result && $this->chTxnUpTime()){
                return true;
            }else{
                return false;
            }
        }

        // update txn medium
        public function updateTxnMedium(){
            $query = "UPDATE transactions set medium = '$this->medium' WHERE id='$this->txn_id'";
            $result = mysqli_query($this->connect, $query);

            if($result && $this->chTxnUpTime()){
                return true;
            }else{
                return false;
            }
        }

        // insert into transaction for topup
        public function saveTopUpTxn(){
            $today = date('Y/m/d H:i:s');
            $query = "INSERT INTO transactions (id, status, amount, description, type, medium, currency, accountNo, createdAt, updatedAt) VALUES ('$this->txn_id', '$this->status', $this->amount,'$this->description', '$this->type', '$this->medium', '$this->currency', $this->accountNo,'$today', '$today')";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }


        public function getTxnToken(){
            $today = date('Y/m/d H:i:s');
            $body = array("txn_id" => $this->txn_id, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 60 minute")));
            return $this->encryptData(json_encode($body));
        }

        public function decodeTxnToken(){
            $decode = $this->decryptData($this->txn_token);
            $rawArr = json_decode($decode, true);

            if($rawArr){
                return $rawArr;
            }else{
                return false;
            }
        }

        public function convertAmt($amt){
            if($this->currency == 'USD'){
                $this->amount = $amt * 7.35;
            }else if($this->currency == 'EUR'){
                $this->amount = $amt * 8.07;
            }else{
                $this->amount = $amt;
            }
        }

        public function chTxnUpTime(){
            $dt = date('Y/m/d H:i:s');
            $query = "UPDATE transactions set updatedAt = '$dt' WHERE id='$this->txn_id'";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        // debit and credit accounts
        public function creditAccount($amount, $accountNo){
            $query = "UPDATE accounts set balance = (balance + $amount) WHERE accountNo ='$accountNo';";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }    
        }

        public function debitAccount($amount, $accountNo){
            $query = "UPDATE accounts set balance = (balance - $amount) WHERE accountNo ='$accountNo';";
            $result = mysqli_query($this->connect, $query);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        // send transaction info to webhook
        public function sendRespondsToWebhook($url){
            $data = array("txn_id" => $this->txn_id, "status" => $this->status, "date" => date('Y/m/d H:i:s'));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
                array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$this->publicKey.''
                ) 
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            if($result){
                return true;
            }else{
                return false;
            }

        }

        // get all user transactions
        public function getTxnForAdmin(){
            $query = "SELECT u.firstname, u.lastname, t.* FROM users u, transactions t, accounts a WHERE u.id = a.user_id AND a.accountNo = t.accountNo;";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return $row;
        }

        // get transfer txn by txnId
        public function getTxnTransByTxnId(){
            $query = "Select * from transfers Where txnId = '$this->txn_id'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // refund txn
        public function refundTxn(){
            $query = "UPDATE transactions set refunded = true WHERE id='$this->txn_id'";
            $result = mysqli_query($this->connect, $query);

            if($result && $this->chTxnUpTime()){
                return true;
            }else{
                return false;
            }
        }
    }

?>