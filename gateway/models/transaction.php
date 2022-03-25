<?php 

    class Transaction {
        public $txn_id;
        public $status;
        public $amount;
        public $description;
        public $type;
        public $webhook;
        public $txn_token;
        public $currency;
        public $accountNo;
        public $createdAT;
        public $updatedAT;
        public $deletedAT;
        public $connect;

        function __construct(){
            require_once './db/config.php';
            $db_Object = new DbConnection;
            $this->connect = $db_Object->connect();
        }


        public function saveInitPaymentTxn(){
            
        }

    }

?>