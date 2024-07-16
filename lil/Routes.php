<?php

namespace Lil;

use App\Config\ConfigClass;

class Routes
{
    private static $routes = [];

    public static function get($path, $func, $name, $middlewares = [])
    {
        self::addRoute('GET', $path, $func, $name, $middlewares);
    }

    public static function post($path, $func, $name, $middlewares = [])
    {
        self::addRoute('POST', $path, $func, $name, $middlewares);
    }

    private static function addRoute($method, $path, $func, $name, $middlewares)
    {
        // Determine if $func is an anonymous function or a class method
        $isFunction = is_callable($func);

        self::$routes[] = [
            "path" => ConfigClass::$base_uri . $path,
            "func" => $isFunction ? $func : [new $func[0], $func[1]],
            "name" => $name,
            "method" => $method,
            "middlewares" => $middlewares,
        ];
    }

    public static function dispatch($url)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            // Check if the method matches
            if ($route['method'] !== $method) {
                continue;
            }

            // Check if it's a POST request
            if ($method === 'POST') {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Check if CSRF token is present and valid
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    // Invalid CSRF token
                    http_response_code(403); // Forbidden status
                    echo "CSRF token validation failed";
                    return;
                }
            }

            // Replace dynamic segments with regex patterns
            $pattern = preg_replace_callback('/{(\w+)}/', function ($matches) {
                return "(?P<{$matches[1]}>\w+)";
            }, $route['path']);

            // Create a regex pattern for the route
            $pattern = "@^" . $pattern . "$@i";

            // Check if the URL matches the route pattern
            if (preg_match($pattern, $url, $matches)) {
                // Remove the full match from $matches
                unset($matches[0]);

                // Call the route handler with parameters
                $params = array_values($matches); // Ensure numeric array keys for compatibility
                $routeMiddlewares = $route["middlewares"];

                foreach ($routeMiddlewares as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $result = $middleware->Run();
                    if ($result === false) {
                        exit();
                    }
                }

                call_user_func_array($route['func'], $params);
                return;
            }
        }

        // If no route matches
        http_response_code(404);
        echo "404 Not Found";
    }

    public static function route($name, $args = [])
    {
        foreach (self::$routes as $route) {
            if ($route['name'] === $name) {
                $path = $route['path'];

                // Replace placeholders with actual arguments
                foreach ($args as $key => $value) {
                    $path = str_replace("{{$key}}", $value, $path);
                }

                return $path;
            }
        }

        return "";
    }
}
