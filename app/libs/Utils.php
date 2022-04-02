<?php

namespace App\Libs;

use App\Libs\MyResponse;

class Utils {
   
    public function __construct() {}
    
    public function getMessageRoute($msg, $code) {
        $dataResponse = new MyResponse();
        $dataResponse->setResponse(false, $msg);
        $dataResponse->code = $code;
        return $dataResponse;
    }
    
    public function responseRoute($res, $data, $code = 200) {
        $res->getBody()->write(json_encode($data));
        $response = $res->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        return $response;
    }
    
    public function validateDate($date) {
        //Se instancia de la siguiente forma porque es una función estática
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
}
