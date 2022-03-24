<?php 

    class Cryptography{
        private $cipherMethod = 'aes-128-cbc-hmac-sha256';
        private $secreteKey = '28e742c6077';
        private $iv = '7f321d7f52c06e73';

        public function encryptData($data){
            $cipher = openssl_encrypt($data, $this->cipherMethod, $this->secreteKey, 0, $this->iv);
            return base64_encode($cipher);
        }

        public function decryptData($data){
            $decode = base64_decode($data);
            $plainText = openssl_decrypt($decode, $this->cipherMethod, $this->secreteKey, 0, $this->iv);
            return $plainText;
        }

        private function uniqidReal($lenght) {
            // uniqid gives 13 chars, but you could adjust it to your needs.
            if (function_exists("random_bytes")) {
                $bytes = random_bytes(ceil($lenght / 2));
            } elseif (function_exists("openssl_random_pseudo_bytes")) {
                $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
            } else {
                throw new Exception("no cryptographically secure random function available");
            }
            return substr(bin2hex($bytes), 0, $lenght);
        }


        public function generateId($lenght){
            return $this->uniqidReal($lenght);
        }

        public function checkSignatureDate($signature){
            $pain = $this->decryptData($signature);
            $rawArray = json_decode($pain, true);

            if($rawArray['edt'] <= date('Y/m/d H:i:s')){
                return true;
            }else{
                return false;
            }
        }

    }

?>