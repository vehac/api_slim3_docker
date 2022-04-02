<?php
$base = __DIR__ . '/../app/';

require $base . '/libs/Database.php';
require $base . '/libs/MyResponse.php';
require $base . '/libs/Utils.php';

require $base . '/middleware/Auth.php';

require $base . '/models/ClientModel.php';