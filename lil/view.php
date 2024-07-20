<?php
use App\Config\ConfigClass;
use Lil\Routes;

// This file contains all the helpers functions for displaying view
function view($viewPath, $data = [])
{
    // The viewPath locates the file relative to the views/src folder
    $path = "views/src/" . $viewPath . ".php";
    return include($path);
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
    // // Start session if not already started
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    //     // session_unset();
    // }

    // // Generate CSRF token if not set
    // if (!isset($_SESSION['csrf_token'])) {
    //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    // }

    // // Return the CSRF token input field
    // return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    return "";
}

function file_upload($upload_dir, $input_name, $allowed_types, $max_size)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $filename = $_FILES[$input_name]['name'];
            $filetype = $_FILES[$input_name]['type'];
            $filesize = $_FILES[$input_name]['size'];

            // Verify MIME type
            if (!in_array($filetype, $allowed_types)) {
                echo "1";
                return false; // Invalid file format
            }

            // Verify file size
            if ($filesize > $max_size) {
                echo "11";

                return false; // File size exceeds the allowed limit
            }

            // Ensure upload directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate a unique file name
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $ext;

            // Move uploaded file to the specified directory
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $upload_path)) {
                return $upload_path; // Return the path where the file is saved
            } else {
                echo "111";

                return false; // Failed to move uploaded file
            }
        } else {
            echo "1111";

            return false; // File upload error
        }
    }
    echo "11111";

    return false; // Not a POST request
}

function file_delete($file_path)
{
    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            return true; // File successfully deleted
        } else {
            return false; // Error deleting file
        }
    } else {
        return false; // File does not exist
    }
}

function file_update($file, $destination, $allowedTypes, $maxSize, $oldFilePath = null)
{

    $newFilePath = file_upload($destination, $file, $allowedTypes, $maxSize);
    echo $newFilePath;
    if ($newFilePath) {
        if ($oldFilePath) {
            file_delete($oldFilePath);
        }
        return $newFilePath;
    }

    return $oldFilePath;
}