<?php

declare(strict_types = 1);
class Router
{
    private array $routes = [];
    private static $instance = null;

    public function getRoutes()
    {
        return json_encode($this->routes);
    }

    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Adding new route
    public function add(string $method, string $path, array $controller)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'controller' => $controller,
            'middlewares' => []
        ];
    }
    // Dispatching an existing route
    public function dispatch(string $path)
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $flag = false;
        foreach ($this->routes as $route) {
            // Check if the path is defined in routes
            if ( !preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method ) {
                continue;
            }

            // Execute if the path matches
            [$controller, $function] = $route['controller'];
            (new $controller())->{$function}();

            // Raise the flag if the path matches
            $flag = true;
        }
        if (!$flag) {
            http_response_code(404);
            (new Controller())->_404();
        }
    }

    private function normalizePath(string $path): string
    {
        $path = preg_replace('/{.*}/', '.+', $path);
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);

        return $path;
    }
}