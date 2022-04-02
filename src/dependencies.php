<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    /*$container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };*/

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
    
    $container['notAllowedHandler'] = function ($c) {
        return function ($req, $res, $methods) use ($c) {
            $res->getBody()->write(json_encode([
                'result' => NULL,
                'message' => 'Method must be one of: ' . implode(', ', $methods),
                'response' => false,
                'code' => 405]));
            $response = $res->withStatus(405)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Allow', implode(', ', $methods));
            return $response;
        };
    };
    
    $container['notFoundHandler'] = function ($c) {
        return function ($req, $res) use ($c) {
            $res->getBody()->write(json_encode([
                'result' => NULL,
                'message' => 'Page not found',
                'response' => false,
                'code' => 404]));
            $response = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            return $response;
        };
    };
};
