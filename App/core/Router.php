<?php

class Router{

    public static function dispatch(){
        $url = $_GET['url'] ?? 'dashboard/index';
        $parts = explode('/', trim($url, '/'));
        $controller = ucfirst($parts[0]) . 'Controller';
        $action = $parts[1] ?? 'index';

        $file = __DIR__ . '/../controllers/' . $controller . '.php';
        if(!file_exists($file)){
            http_response_code(404);
            die('Controller não encontrado');
        }
        require $file;

        if(!class_exists($controller)){
            http_response_code(500);
            die('Controller inválido.');
        }

        $instance = new $controller();
        if(!method_exists($instance, $action)){
            http_response_code(404);
            die('Ação não encontrada.');
        }

        $instance->$action();
    }
}