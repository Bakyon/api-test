<?php
class Controller {
    function show($view, $data = null, $layout = 'shared_layout') {
        include 'views/layouts/' . $layout . '.php';
    }
    function _404() {
        include 'views/core/404.php';
    }
}