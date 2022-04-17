<?php 

    require_once './models/transaction.php';

    class BankCard extends Transaction{
        public $card_no;
        public $cvv;
        public $expiry_mm;
        public $expiry_yy;
        public $bankAcct_No;
        public $bankName;
        public $connect;
        public $thetaBankNum = thetaBankNumber;

        function __construct(){
            require_once './db/config.php';
            $db_Object = new DbConnection;
            $this->connect = $db_Object->connect();
            $this->card_no = $this->cardNo ?? 0;
        }

        // get bankCard by cardno
        public function getCardById(){
            $query = "Select * from cards Where card_no = '$this->card_no'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        // validate cardinfo
        public function validateCardParam($cardInfo, $reqBody){
            if($cardInfo["holder_name"] != $reqBody["name"]){
                return "Invalid card holder name";
            }else if($cardInfo["cvv"] != $reqBody["cvv"]){
                return "Invalid CVV";
            }else if($cardInfo["expiry_mm"] != $reqBody["expiry_mm"]){
                return "Invalid expiration month";
            }else if($cardInfo["expiry_yy"] != $reqBody["expiry_yy"]){
                return "Invalid expiration year";
            }else{
                return true;
            }
        }

        // transfer money from card
        public function debitACard($amount){
            $query = "UPDATE cards set balance = (balance - $amount) WHERE card_no ='$this->card_no';";
            $result = mysqli_multi_query($this->connect, $query);

            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }        
        }

        public function creditCard($amount){
            $query = "UPDATE cards set balance = (balance + $amount) WHERE card_no ='$this->card_no';";
            $result = mysqli_multi_query($this->connect, $query);

            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }     
        }

        // debit and credit thetaPay account
        public function debitMainAcct($amount){
            $query = "UPDATE cards set balance = (balance - $amount) WHERE bankAcct_No ='$this->thetaBankNum';";
            $result = mysqli_multi_query($this->connect, $query);

            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }        
        }

        public function creditMainAcct($amount){
            $query = "UPDATE cards set balance = (balance + $amount) WHERE bankAcct_No ='$this->thetaBankNum';";
            $result = mysqli_multi_query($this->connect, $query);

            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }     
        }

        // insert to card payment
        public function insertIntoCardPayment(){
            $query = "INSERT INTO card_payment (txnId, card_no) VALUES ('$this->txn_id', '$this->card_no')";
            $result = mysqli_query($this->connect, $query);

            echo mysqli_error($this->connect);

            if($result){
                return true;
            }else{
                return false;
            }
        }

        // get card payment by txn_id
        public function getCardPaymentByTxnId($txnId){
            $query = "SELECT c.holder_name, c.balance, cp.txnId FROM cards c, card_payment cp, transactions t WHERE t.id = cp.txnId AND c.card_no = cp.card_no AND t.id = '$txnId'";
            $result = mysqli_query($this->connect, $query);
            $row = mysqli_fetch_assoc($result);

            return $row;
        }
    }

?>