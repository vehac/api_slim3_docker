<?php

namespace App\Middleware;

use Firebase\JWT\JWT;

use App\Libs\Utils;

class Auth {
    
    public function __invoke($req, $res, $next) {
        try {
            $jwtHeader = $req->getHeaderLine('Authorization');
            if(!$jwtHeader) {
                throw new \Exception('JWT Token requerido.', 400);
            }
            $jwt = explode('Bearer ', $jwtHeader);
            if(!isset($jwt[1])) {
                throw new \Exception('JWT Token no existe.', 400);
            }
            $jwt_trim = preg_replace('/\s+/',' ', trim($jwt[1]));

            $decoded = $this->validateToken($jwt_trim);
            if($decoded->sub != JWT_ID) {
                throw new \Exception('JWT Token inválido.', 400);
            }
            $object = (array) $req->getParsedBody();
            $object['decoded'] = $decoded;
            return $next($req->withParsedBody($object), $res);
        }catch(\Exception $e) {
            $utils = new Utils();
            $dataResponse = $utils->getMessageRoute($e->getMessage(), $e->getCode());
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    }
    
    private function validateToken($token) {
        try {
            return JWT::decode(trim($token), JWT_SECRET_KEY, ['HS256']);
        }catch(\Exception $e) {
            throw new \Exception('Forbidden: no autorizado.', 403);
        }
    }
}
