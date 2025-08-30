<?php
session_start();

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Auth.php';
require __DIR__ . '/../app/core/Router.php';

require __DIR__ . '/../app/models/User.php';
require __DIR__ . '/../app/models/Ticket.php';

Router::dispatch();
