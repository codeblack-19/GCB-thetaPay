<?php 

    class Cryptography{
        private $cipherMethod = thetaCipherMethod;
        private $iv = thetaIV;

        public function encryptData($data, $secreteKey){
            $cipher = openssl_encrypt($data, $this->cipherMethod, $secreteKey, 0, $this->iv);
            return base64_encode($cipher);
        }

        public function decryptData($data, $secreteKey){
            $decode = base64_decode($data);
            $plainText = openssl_decrypt($decode, $this->cipherMethod, $secreteKey, 0, $this->iv);
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

        public function checkSignatureDate($signature, $secreteKey){
            $pain = $this->decryptData($signature, $secreteKey);
            $rawArray = json_decode($pain, true);

            if($rawArray['edt'] <= date('Y/m/d H:i:s')){
                return true;
            }else{
                return false;
            }
        }

    }

?>