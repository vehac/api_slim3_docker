<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
require __DIR__ . '/../src/config.php';
require __DIR__ . '/../src/database.php';

$settings = require __DIR__ . '/../src/settings.php';
$settings['settings']['db'] = $config['db'];
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

//Usado en el index.php
//$container = $app->getContainer();
//$container->logger->addInfo('Usado en el index.php');

//Usado fuera del index.php
//$this->logger->addInfo('Usado fuera del index.php');

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

require __DIR__ . '/../app/app_loader.php';

$route_init = require __DIR__ . '/../app/routes/init.php';
$route_init($app);

$route_auth = require __DIR__ . '/../app/routes/v1/auth.php';
$route_auth($app);

$route_client = require __DIR__ . '/../app/routes/v1/clients.php';
$route_client($app);

// Run app
$app->run();
