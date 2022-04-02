<?php

use \Slim\App;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

use App\Libs\Utils;
use App\Middleware\Auth;
use App\Libs\MyResponse;

return function (App $app) {
    
    /**
    * @api {post} /v1/generate-token Generar token
    * @apiName GenerateToken
    * @apiVersion 1.0.0
    * @apiGroup Auth
    * @apiDescription Endpoint que permite generar token para usarlo en los endpoints que necesitan autorización.
    *
    * 
    * @apiBody {String} user Usuario para generar token (JWT_USER).
    * @apiBody {String} pass Password para generar token (JWT_PASSWORD).
    *
    * 
    * @apiSuccess {Json} result Datos de token.
    * @apiSuccess {String} token Código de autorización, este token se usará en los endpoints que necesitan autorización.
    * @apiSuccess {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    * HTTP/1.1 200 OK
    *    {
    *        "result": {
    *            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjk4NTM3NzcsImlhdCI6MTY0ODc5MjYwNCwuYXhwIjoxNjQ4ODc5MDA0fQ.rk8pxeMTRlCLzEtU5ozorxE4K0fTjmwnW0FefVowcL0"
    *        },
    *        "response": true,
    *        "message": "Token generado.",
    *        "code": 200
    *    }
    *
    * 
    * @apiError {NULL} result NULL.
    * @apiError {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiError {String} message  Mensaje informativo o de error.
    * @apiError {Integer} code  código HTTP.
    *
    * 
    * @apiErrorExample {Json} Error-Response:
    * HTTP/1.1 400 Bad Request
    *    {
    *        "result": null,
    *        "response": false,
    *        "message": "Parámetros inválidos.",
    *        "code": 400
    *    }
    */
    $app->post('/v1/generate-token', function(Request $req, Response $res, $args) {
        $utils = new Utils();
        try {
            $data = $req->getParsedBody();
            if(!isset($data['user'])) {
                throw new \Exception('El campo user es requerido.', 400);
            }
            if(!isset($data['pass'])) {
                throw new \Exception('El campo pass es requerido.', 400);
            }
            if($data['user'] != JWT_USER || $data['pass'] != JWT_PASSWORD) {
                throw new \Exception('Parámetros inválidos.', 400);
            }
            $date = new \DateTime();
            $dataToken['sub']    = JWT_ID;
            $dataToken['iat']    = $date->getTimestamp();
            $dataToken['exp']    = JWT_EXP;
            $token = JWT::encode($dataToken, JWT_SECRET_KEY);
            
            $res_data = new MyResponse();
            $res_data->setResponse(true, "Token generado.");
            $res_data->code = 200;
            $res_data->result = ["token" => $token];
            return $utils->responseRoute($res, $res_data, $res_data->code);
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), $e->getCode());
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    });
    
    /**
    * @api {post} /v1/verify-token Verificar token
    * @apiName VerifyToken
    * @apiVersion 1.0.0
    * @apiGroup Auth
    * @apiDescription Endpoint que permite verificar si el token está activo.
    *
    * 
    * @apiHeader {String} token Generado con endpoint generate-token.
    * 
    * 
    * @apiHeaderExample {Json} Header-Example:
    *    {
    *        "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjk4NTN7NzcsImlhdCI6MTY0ODc5MDU4MCwiZXhwIjoxNjQ4ODc2OTgwfQ.yVVjfeIl1DrEF5sjXtY7DzrTct3Lh3cORXh2xH6hqzg"
    *    }
    *
    * 
    * @apiSuccess {Json} result Datos de token.
    * @apiSuccess {Boolean} is_active Indica si token está activo (true).
    * @apiSuccess {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    * HTTP/1.1 200 OK
    *    {
    *        "result": {
    *            "is_active": true
    *        },
    *        "response": true,
    *        "message": "Token activo.",
    *        "code": 200
    *    }
    *
    * 
    * @apiError {NULL} result NULL.
    * @apiError {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiError {String} message  Mensaje informativo o de error.
    * @apiError {Integer} code  código HTTP.
    *
    * 
    * @apiErrorExample {Json} Error-Response:
    * HTTP/1.1 400 Bad Request
    *   {
    *        "result": null,
    *        "response": false,
    *        "message": "Forbidden: no autorizado.",
    *        "code": 403
    *   }
    */
    $app->post('/v1/verify-token', function(Request $req, Response $res, $args) {
        $utils = new Utils();
        try {
            $data = $req->getParsedBody();
            if(!isset($data['decoded']->sub)) {
                throw new \Exception('Token a expirado.', 400);
            }
            $res_data = new MyResponse();
            $res_data->setResponse(true, "Token activo.");
            $res_data->code = 200;
            $res_data->result = ["is_active" => true];
            return $utils->responseRoute($res, $res_data, $res_data->code);
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), $e->getCode());
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    })->add(new Auth());
};