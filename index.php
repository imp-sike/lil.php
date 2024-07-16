<?php
use Stevel\Routes;

require 'vendor/autoload.php';
require 'stevel/view.php';
require 'routes/web.php';


// Get the current URL path
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch the route
Routes::dispatch($url);