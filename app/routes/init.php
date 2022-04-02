<?php

use \Slim\App;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

return function (App $app) {
    
    $app->get('/', function (Request $req, Response $res, array $args) {
        $data['title']= 'Project API SLIM...';
        $res->getBody()->write(json_encode($data));
        $response = $res->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        return $response;
    });
};