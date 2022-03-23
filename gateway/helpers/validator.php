<?php 

    class Validator{
        private $notInbody = array();

        public function validateBody($reqbody, $bodydata){
            foreach($bodydata as $key){
                // check if key in body
                if(!in_array($key, array_keys($reqbody))){
                    $this->notInbody[] = array("error" => "$key is required");
                }else if(empty($reqbody[$key])){
                    // check for null
                    $this->notInbody = array("error" => "$key cannot be null");
                }
            }

            return $this->notInbody;
        }
    }

?>