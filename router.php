<?php

class Router{

    public $routes = array();

    private function registerRoute( $method, $url, $fn ) {
        print_r($url); die();
        $this->routes[$url] = array(
            'fn'     => $fn,
            'method' => $method,
            'url'    => $url,
        ); 

    }

    public function Handler( $method, $url, $fn ) {
        $this->registerRoute( $method, $url, $fn );
    }

    public function get( $url, $fn ) {
        $this->registerRoute( 'GET', $url, $fn );
    }

    public function post( $url, $fn ) {
        $this->registerRoute( 'POST', $url, $fn );
    }

    public function put( $url, $fn ) {
        $this->registerRoute( 'PUT', $url, $fn );
    }

    public function delete( $url, $fn ) {
        $this->registerRoute( 'DELETE', $url, $fn );
    }

    public function register404( $url, $fn ) {
        $this->registerRoute( 'GET', $url, $fn );
    }

    public function run(){
        $this->checkPattern();
    }

    private function checkPattern() {

        $uri = $_SERVER['REQUEST_URI'];

        if( $this->routes[$uri] ) {
            $route = $this->routes[$uri];
            $res = $this->Method( $route['method'] );

            if( $res ) {
                call_user_func( $route['fn'] );
            }
            return;
        }

        http_response_code(404);
    }

    public function Method( $method ) {

        switch( $method ) {
            case 'POST':
                $res = $this->checkMethod( $method );

                if( ! $res ) {
                    http_response_code(400);
                    exit;
                }
                return  $res;
              
            case 'GET':
                $res = $this->checkMethod( $method );

                if( ! $res ) {
                    http_response_code(400);
                    exit;
                }
                return  $res;
        }

    }

    protected function checkMethod( $method ) {
        if( $_SERVER['REQUEST_METHOD'] == $method ) {
            return $method;
        }

        return false;
    }

}


?>