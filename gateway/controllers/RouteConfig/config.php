<?php 

    class Route{
        public static $validRoutes = array();

        public static function isRouteValid() {
            $url =  self::getRoute();

            if (!in_array($url, self::$validRoutes)) {
                http_response_code(400);
                echo 'invalid url';
                return;
            } else {
                return 1;
            }
        }

        public static function base($route, $function){
            self::$validRoutes[] = $route;

            $url =  self::getRoute();

            if($url == $route){
                $function->__invoke();
            }
        }

        public static function auth($route, $middleware, $function){
            self::$validRoutes[] = $route;
            $url =  self::getRoute();

            if($url == $route){
                $middleware->__invoke();
                $function->__invoke();
            }
        }

        private function getRoute(){
            $slit = explode('/GCB-thetaPay/gateway', $_SERVER['REQUEST_URI']);
            $main = explode('?', $slit[1]);
            return $main[0];
        }

    }

?>