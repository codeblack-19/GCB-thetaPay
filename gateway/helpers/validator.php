<?php 

    class Validator{
        private $notInbody = array();

        public function validateBody($reqbody, $bodydata){
            foreach($bodydata as $key){
                // check if key in body
                if(!in_array($key, array_keys($reqbody))){
                    $this->notInbody[] = array("error" => "$key is required in request body");
                }else if(empty($reqbody[$key])){
                    // check for null
                    $this->notInbody = array("error" => "$key cannot be null");
                }else if($key == 'currency' && !in_array($reqbody[$key], array("GHS", "USD", "EUR"))){
                    $this->notInbody = array("error" => "$reqbody[$key] => is not a supported currency in thetaPay");
                }
            }

            return $this->notInbody;
        }

        public function validateQueryStrings($reqbody, $bodydata){
            foreach($bodydata as $key){
                // check if key in body
                if(!in_array($key, array_keys($reqbody))){
                    $this->notInbody[] = array("error" => "$key is required in query strings");
                }else if(empty($reqbody[$key])){
                    // check for null
                    $this->notInbody = array("error" => "$key cannot be null");
                }
            }

            return $this->notInbody;
        }
    }

?>