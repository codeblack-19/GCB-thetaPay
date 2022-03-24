<?php 

    class ViewController{
        public static function CreateView($viewName){
            require_once("./views/$viewName.php");
        }

        public static function CreateViewWithParams($viewName){
            $main = explode('?', $viewName);
            $parts = explode('&', $main[1]);
            foreach($parts as $part){
                parse_str($part, $output);
                foreach($output as $key => $value){
                    $_GET[$key] = $value;
                }
            }

            require_once('./views/'.$main[0].'.php');
        }
    }

?>