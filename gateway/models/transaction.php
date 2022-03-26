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
        public $createdAt;
        public $updatedAt;
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

        public function getTxnToken(){
            $today = date('Y/m/d H:i:s');
            $body = array("txn_id" => $this->txn_id, "cdt" => $today, "edt" => date('Y/m/d H:i:s', strtotime( $today. " + 60 minute")));
            return $this->encryptData(json_encode($body));
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

    }

?>