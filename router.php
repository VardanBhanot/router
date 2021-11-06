<?php

class Router{

    public $routes = array();

    /**
     * Registers Routes
     *
     * @param [string] $method
     * @param [string] $url
     * @param [string] $fn
     * @return void
     */
    private function registerRoute( $method, $url, $fn ) {
        $this->routes[$url] = array(
            'fn'     => $fn,
            'method' => $method,
            'url'    => $url,
            'hasVar' => false,
        );
        
        if( preg_match('/\{(.*?)\}/', $url) ) {
            $this->routes[$url]['hasVar'] = true;
        }
       
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

    // Runs the router to access routes
    public function run(){
        $this->checkPattern();
    }

    /**
     * Checks for the requested URL in the Registered URL and executes
     * the required function if match is found.
     *
     * @return void
     */
    private function checkPattern() {

        $uri = $_SERVER['REQUEST_URI'];
       
        if( !empty( $uri ) ) {
            $matchedURI = $this->matchURI( $uri );
            
            if( $matchedURI  && isset( $matchedURI['url'] ) ) {
                $route = $matchedURI['url'];
                $res = $this->Method( $route['method'] );
                
                if( $res && isset($matchedURI['var']) ) {
                    call_user_func( $route['fn'], $matchedURI['var'] );
                } elseif( $res ) {
                    call_user_func( $route['fn'] );
                } else{
                    http_response_code(404);
                    exit;
                }
            }  
        } 

        http_response_code(404);
    }

    /**
     * Matches the URI
     *
     * @param [string] $uri
     * @return [array/bool]
     */
    private function matchURI( $uri ) {
        
        if( isset($this->routes[$uri]) ) {
            return ['url' => $this->routes[$uri], 'var' => ''];
        }
        
        foreach( $this->routes as $route ) {
            if( !$route['hasVar']) {
                continue;
            }
        
            $matched = $this->matchPath($route['url'], $uri);
           
            if( $matched && $matched['url'] ) {
                $url = $matched['url'];
                return ['url' => $this->routes[$url], 'var' => $matched['var']];
            }
        }

        return false;
    }

    /**
     * Matches the path of the URI
     *
     * @param [string] $regURL
     * @param [string] $reqURL
     * @return [array/bool]
     */
    private function matchPath($regURL, $reqURL) {
        $url = $regURL;
        $reqURL = explode( '/', parse_url($reqURL)['path'] );
        $regURL = explode( '/', parse_url($regURL)['path'] );

        if( count($reqURL) != count($regURL) ) {
            return false;
        }
        
        for( $i = 0; $i < count($reqURL); $i++ ) {
            $variable = '';

            if( $reqURL[$i] == $regURL[$i] ) {
                continue;
            } elseif( preg_match('/\{(.*?)\}/', $regURL[$i]) ) {
                $variable = $reqURL[$i];
                continue;
            } else{
                return false;
            }
        }

        if( empty($variable) ) {
            return false;
        }

        return ['url' => $url, 'var' => $variable]; 
    }

    /**
     * Chesks for reuested HTTP Method
     *
     * @param [string] $method
     * @return [string/bool]
     */
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

    /**
     * Checks if requested method matches registered method.
     *
     * @param [string] $method
     * @return [string/bool]
     */
    protected function checkMethod( $method ) {

        if( $_SERVER['REQUEST_METHOD'] == $method ) {
            return $method;
        }

        return false;
    }
}
?>