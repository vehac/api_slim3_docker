<?php
return [
    'settings' => [
        'displayErrorDetails' => TRUE, // set to false in production, para prod colocar en FALSE
        'addContentLengthHeader' => FALSE, // Allow the web server to send the content-length header

        // Renderer settings
        /*'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],*/

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
