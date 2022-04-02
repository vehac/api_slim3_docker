<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    /*$container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get('/hello/{name}', function (Request $req, Response $res, array $args) {
        $this->logger->addInfo('Ejecutando endpoint get hello');
        $name = $args['name'];
        $res->getBody()->write("Hello, $name");
        return $response;
    });*/
};
