<?php

session_start();

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/core/Router.php';


Router::dispatch();
