<?php
define('__ROOT__', dirname(dirname(__FILE__)));

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $response['method'] = 'POST';
            $response['message'] = "Upload File";

            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['fileToUpload']['name']);
        
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
        
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadFile)) {
                $response["data"] = "File uploaded successfully.";
                $response["status"] = "success";
            } else {
                $response["status"] = "failed";
                $response["data"] = "File uploaded failed.";
            }
            break;
        default:
             // Handle other methods
            $response['method'] = $_SERVER['REQUEST_METHOD'];
            $response['message'] = "Unhandled request method";
            break;
    }
    return $response;
}

header('Content-Type: application/json');
echo json_encode(handle_request());