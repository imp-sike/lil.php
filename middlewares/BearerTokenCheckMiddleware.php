<?php

namespace App\Middleware;

use Stevel\Middleware;

class BearerTokenCheckMiddleware implements Middleware
{
    public function Run()
    {
        // Get the authorization header
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        // echo $authorizationHeader;

        // Check if the authorization header is present and formatted correctly
        if (strpos($authorizationHeader, 'Bearer ') === 0) {
            // Extract the token
            $token = substr($authorizationHeader, 7);

            // echo $token;

            // Validate the token (example validation)
            if ($token === 'apikey') {
                // Token is valid
                return true;
                // Proceed with your logic
            } else {
                // Token is not valid
                http_response_code(401); // Unauthorized
                echo json_encode(["message" => "Unauthorized"]);
                return false;
            }
        } else {
            // Authorization header missing or incorrect format
            http_response_code(400); // Bad request
            echo json_encode(["message" => "Bad Request"]);
            return false;
        }
    }
}