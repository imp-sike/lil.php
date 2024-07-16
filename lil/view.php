<?php
use App\Config\ConfigClass;
use Lil\Routes;

// This file contains all the helpers functions for displaying view
function view($viewPath, $data = [])
{
    // The viewPath locates the file relative to the views/src folder
    $path = "views/src/" . $viewPath . ".php";
    return include ($path);
}

function json($content)
{
    // The viewPath locates the file relative to the views/src folder
    header("Content-Type: application/json");
    echo json_encode($content);
}

function route($routeName, $data = [])
{
    if (count($data) > 0) {
        return Routes::route($routeName, $data);
    }
    return Routes::route($routeName);
}

function redirect($route)
{
    header("Location: $route");
}

function mustHave($method, $validateArray)
{
    $result = [];
    foreach ($validateArray as $key) {
        if (isset($method[$key]) && !empty($method[$key])) {
            $result[$key] = $method[$key]; // Fill the result array with data
        } else {
            return false; // Return false if any required key is missing or empty
        }
    }
    return $result; // Return the associative array with filled data
}

function csrf()
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        session_unset();
    }

    // Generate CSRF token if not set
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Return the CSRF token input field
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}
