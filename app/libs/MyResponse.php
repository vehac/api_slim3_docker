<?php

namespace App\Libs;

class MyResponse {
    
    public $result      = null;
    public $response    = false;
    public $message     = 'Ocurrio un error inesperado...';
    public $code        = 200;
    
    public function __construct() {}
    
    public function SetResponse($response, $m = '') {
        $this->response = $response;
        $this->message = $m;
        if(!$response && $m = '') {
            $this->response = 'Ocurrio un error inesperado...';
        }
    }
}
