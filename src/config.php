<?php
$date = new \DateTime();

defined('JWT_SECRET_KEY')   OR define('JWT_SECRET_KEY', '12345jhytgruiopmnbvcxzasdmncdes67890');
defined('JWT_USER') OR define('JWT_USER', 'user_api');
defined('JWT_PASSWORD') OR define('JWT_PASSWORD', 'pass7fgiy7udeg7word');
defined('JWT_EXP')  OR define('JWT_EXP', $date->getTimestamp() + 60*60*24); //24 horas activo
defined('JWT_ID')   OR define('JWT_ID', 9853777);