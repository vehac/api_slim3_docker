<?php

use \Slim\App;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\ClientModel;
use App\Libs\Utils;
use App\Middleware\Auth;

return function (App $app) {
    $container = $app->getContainer();

    /**
    * @api {get} /v1/clients Listar clientes
    * @apiName GetClients
    * @apiVersion 1.0.0
    * @apiGroup Client
    * @apiDescription Endpoint que permite listar clientes.
    *
    *
    * @apiSuccess {Json} result Lista de clientes.
    * @apiSuccess {String} id Id de cliente.
    * @apiSuccess {String} name Nombre de cliente.
    * @apiSuccess {String} lastname Apellidos de cliente.
    * @apiSuccess {String} gender  Género de cliente ( M - F).
    * @apiSuccess {String} description  Descripción de cliente.
    * @apiSuccess {String} phone  Teléfono de cliente.
    * @apiSuccess {String} country  Ciudad de cliente.
    * @apiSuccess {String} address  Dirección de cliente.
    * @apiSuccess {String} birth_date  Fecha de nacimiento de cliente.
    * @apiSuccess {String} created_at  Fecha y hora que se creó registro de cliente.
    * @apiSuccess {String} updated_at  Fecha y hora que se actualiza registro de cliente.
    * @apiSuccess {Boolean} response  Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo o de error.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    *  HTTP/1.1 200 OK
    *    {
    *        "result": [
    *            {
    *                "id": "1",
    *                "name": "Luis",
    *                "lastname": "Vargas Vargas",
    *                "gender": "M",
    *                "description": "Esta es una descripción del cliente Luis",
    *                "phone": "948252525",
    *                "country": "Perú",
    *                "address": "Los jardines #777",
    *                "birth_date": "1986-12-01",
    *                "created_at": "2022-03-27 08:55:21",
    *                "updated_at": null
    *            }
    *        ],
    *        "response": true,
    *        "message": "Listado realizado con éxito.",
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
    *        "message": "No existen clientes.",
    *        "code": 400
    *    }
    */
    $app->get('/v1/clients', function(Request $req, Response $res) use ($container) {
        $utils = new Utils();
        try {
            $this->logger->addInfo("Listando clientes.");
            $clientModel = new ClientModel($container);
            $client = $clientModel->getClients();
            $response = $utils->responseRoute($res, $client, $client->code);
            return $response;
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), 500);
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    });
    
    /**
    * @api {post} /v1/clients/new Registrar cliente
    * @apiName NewClient
    * @apiVersion 1.0.0
    * @apiGroup Client
    * @apiDescription Endpoint que permite registrar cliente.
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
    * @apiBody {String} name Nombre de cliente.
    * @apiBody {String} lastname Apellidos de cliente.
    * @apiBody {String} gender  Género de cliente ( M - F).
    * @apiBody {String} description  Descripción de cliente.
    * @apiBody {String} phone  Teléfono de cliente.
    * @apiBody {String} country  Ciudad de cliente.
    * @apiBody {String} address  Dirección de cliente.
    * @apiBody {String} birth_date  Fecha de nacimiento de cliente.
    *
    * 
    * @apiSuccess {NULL} result NULL.
    * @apiSuccess {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    *  HTTP/1.1 200 OK
    *    {
    *        "result": null,
    *        "response": true,
    *        "message": "Cliente creado con éxito.",
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
    *        "message": "No existen datos para crear cliente.",
    *        "code": 400
    *    }
    */
    $app->post('/v1/clients/new', function(Request $req, Response $res) use ($container) {
        $utils = new Utils();
        try {
            $data = $req->getParsedBody();
            if(count($data) <= 0) {
                $dataResponse = $utils->getMessageRoute("No existen datos para crear cliente.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $client_data = [];
            //$valid_date = $utils->validateDate("2000-11-12");
            $client_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $client_data['lastname'] = filter_var($data['lastname'], FILTER_SANITIZE_STRING);
            $client_data['gender'] = filter_var($data['gender'], FILTER_SANITIZE_STRING);
            $client_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
            $client_data['phone'] = filter_var($data['phone'], FILTER_SANITIZE_STRING);
            $client_data['country'] = filter_var($data['country'], FILTER_SANITIZE_STRING);
            $client_data['address'] = filter_var($data['address'], FILTER_SANITIZE_STRING);
            $client_data['birth_date'] = filter_var(preg_replace("[^0-9-]","",htmlentities($data['birth_date'])));
            $clientModel = new ClientModel($container);
            $client = $clientModel->saveClient($client_data);
            return $utils->responseRoute($res, $client, $client->code);
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), 500);
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    })->add(new Auth());
    
    /**
    * @api {post} /v1/clients/update/:id Actualizar cliente
    * @apiName UpdateClient
    * @apiVersion 1.0.0
    * @apiGroup Client
    * @apiDescription Endpoint que permite actualizar cliente.
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
    * @apiParam {Integer} id Id de cliente a actualizar.
    *
    *  
    * @apiBody {String} name Nombre de cliente.
    * @apiBody {String} lastname Apellidos de cliente.
    * @apiBody {String} gender  Género de cliente ( M - F).
    * @apiBody {String} description  Descripción de cliente.
    * @apiBody {String} phone  Teléfono de cliente.
    * @apiBody {String} country  Ciudad de cliente.
    * @apiBody {String} address  Dirección de cliente.
    * @apiBody {String} birth_date  Fecha de nacimiento de cliente.
    *
    * 
    * @apiSuccess {NULL} result NULL.
    * @apiSuccess {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    *  HTTP/1.1 200 OK
    *    {
    *        "result": null,
    *        "response": true,
    *        "message": "Cliente actualizado con éxito.",
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
    *        "message": "No se pudo actualizar cliente.",
    *        "code": 400
    *    }
    */
    $app->post('/v1/clients/update/{id}', function(Request $req, Response $res, $args) use ($container) {
        $utils = new Utils();
        try {
            if(!isset($args['id']) || !is_numeric($args['id'])) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $client_id = (int) $args['id'];
            if($client_id <= 0) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $data = $req->getParsedBody();
            if(count($data) <= 0) {
                $dataResponse = $utils->getMessageRoute("No existen datos para crear cliente.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $client_data = [];
            //$valid_date = $utils->validateDate("2000-11-12");
            $client_data['id'] = $client_id;
            $client_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $client_data['lastname'] = filter_var($data['lastname'], FILTER_SANITIZE_STRING);
            $client_data['gender'] = filter_var($data['gender'], FILTER_SANITIZE_STRING);
            $client_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
            $client_data['phone'] = filter_var($data['phone'], FILTER_SANITIZE_STRING);
            $client_data['country'] = filter_var($data['country'], FILTER_SANITIZE_STRING);
            $client_data['address'] = filter_var($data['address'], FILTER_SANITIZE_STRING);
            $client_data['birth_date'] = filter_var(preg_replace("[^0-9-]","",htmlentities($data['birth_date'])));
            $clientModel = new ClientModel($container);
            $client = $clientModel->saveClient($client_data);
            return $utils->responseRoute($res, $client, $client->code);
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), 500);
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    })->add(new Auth());
    
    /**
    * @api {post} /v1/clients/delete/:id Eliminar cliente
    * @apiName DeleteClient
    * @apiVersion 1.0.0
    * @apiGroup Client
    * @apiDescription Endpoint que permite eliminar cliente.
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
    * @apiParam {Integer} id Id de cliente a eliminar.
    *
    * 
    * @apiSuccess {NULL} result NULL.
    * @apiSuccess {Boolean} response Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    *  HTTP/1.1 200 OK
    *    {
    *        "result": null,
    *        "response": true,
    *        "message": "Eliminación realizada con éxito.",
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
    *        "message": "Cliente no existe.",
    *        "code": 400
    *    }
    */
    $app->post('/v1/clients/delete/{id}', function(Request $req, Response $res, $args) use ($container) {
        $utils = new Utils();
        try {
            if(!isset($args['id']) || !is_numeric($args['id'])) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $client_id = (int) $args['id'];
            if($client_id <= 0) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $clientModel = new ClientModel($container);
            $client = $clientModel->deleteClient($client_id);
            return $utils->responseRoute($res, $client, $client->code);
        }catch (\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), 500);
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    })->add(new Auth());
    
    /**
    * @api {get} /v1/clients/:id Datos cliente
    * @apiName GetClient
    * @apiVersion 1.0.0
    * @apiGroup Client
    * @apiDescription Endpoint que permite listar un cliente.
    *
    * 
    * @apiParam {Integer} id Id de cliente.
    *
    * 
    * @apiSuccess {Json} result Datos del cliente.
    * @apiSuccess {String} id Id de cliente.
    * @apiSuccess {String} name Nombre de cliente.
    * @apiSuccess {String} lastname Apellidos de cliente.
    * @apiSuccess {String} gender  Género de cliente ( M - F).
    * @apiSuccess {String} description  Descripción de cliente.
    * @apiSuccess {String} phone  Teléfono de cliente.
    * @apiSuccess {String} country  Ciudad de cliente.
    * @apiSuccess {String} address  Dirección de cliente.
    * @apiSuccess {String} birth_date  Fecha de nacimiento de cliente.
    * @apiSuccess {String} created_at  Fecha y hora que se creó registro de cliente.
    * @apiSuccess {String} updated_at  Fecha y hora que se actualiza registro de cliente.
    * @apiSuccess {Boolean} response  Indica si se realizó la operación (true) o no se realizó (false).
    * @apiSuccess {String} message  Mensaje informativo o de error.
    * @apiSuccess {Integer} code  código HTTP.
    *
    * 
    * @apiSuccessExample {Json} Success-Response:
    *  HTTP/1.1 200 OK
    *   {
    *        "result": {
    *            "id": "1",
    *            "name": "Luis",
    *            "lastname": "Vargas Vargas",
    *            "gender": "M",
    *            "description": "Esta es una descripción del cliente Luis",
    *            "phone": "948252525",
    *            "country": "Perú",
    *            "address": "Los jardines #777",
    *            "birth_date": "1986-12-01",
    *            "created_at": "2022-03-27 08:55:21",
    *            "updated_at": null
    *        },
    *        "response": true,
    *        "message": "Cliente retornado con éxito.",
    *        "code": 200
    *   }
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
    *       "result": null,
    *       "response": false,
    *       "message": "Cliente no existe.",
    *       "code": 400
    *   }
    */
    $app->get('/v1/clients/{id}', function(Request $req, Response $res, $args) use ($container) {
        $utils = new Utils();
        try {
            if(!isset($args['id']) || !is_numeric($args['id'])) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $client_id = (int) $args['id'];
            if($client_id <= 0) {
                $dataResponse = $utils->getMessageRoute("Id no válido.", 400);
                return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
            }
            $clientModel = new ClientModel($container);
            $client = $clientModel->getClientById($client_id);
            return $utils->responseRoute($res, $client, $client->code);
        }catch(\Exception $e) {
            $dataResponse = $utils->getMessageRoute($e->getMessage(), 500);
            return $utils->responseRoute($res, $dataResponse, $dataResponse->code);
        }
    });
};