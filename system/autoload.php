<?php
session_start();
ob_start();

// Adding core config to web service
include 'system/config/config.php';
include 'system/libs/functions.php';
include 'system/router/Router.php';
include 'system/router/web.php';

spl_autoload_register(function ($class) {
    $libs_path = 'system/libs/' . $class . '.php';
    $systemdb_path = 'Repository/'.$class.'.php';
    $controller_path = 'Controller/'.$class.'.php';
    $repository_path = 'system/Repository/'.$class.'.php';
    $model_path = 'Model/'.$class.'.php';

    if (file_exists($controller_path)) {
        require_once $controller_path;
    }
    if (file_exists($model_path)) {
        require_once $model_path;
    }
    if (file_exists($systemdb_path)) {
        require_once $systemdb_path;
    }
    if (file_exists($repository_path)) {
        require_once $repository_path;
    }
    if (file_exists($libs_path)) {
        require_once $libs_path;
    }
});