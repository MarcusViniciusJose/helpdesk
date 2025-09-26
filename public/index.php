<?php
declare(strict_types=1);

session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../config/config.php';


require_once __DIR__ . '/../app/core/Router.php';

Router::dispatch();
