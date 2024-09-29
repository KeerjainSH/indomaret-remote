<?php
define('__ROOT__', dirname(dirname(__FILE__)));

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $response['method'] = 'POST';
            $response['message'] = "Upload File";

            $fileType = $_POST['type'];

            if ($fileType == "csv") {
                $uploadDir = 'uploads_csv/';
                $uploadFile = $uploadDir . basename($_FILES['fileToUploadCsv']['name']);
            
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
            
                if (move_uploaded_file($_FILES['fileToUploadCsv']['tmp_name'], $uploadFile)) {
                    $response["data"] = "File uploaded successfully.";
                    $response["status"] = "success";
                } else {
                    $response["status"] = "failed";
                    $response["data"] = "File uploaded failed.";
                }
                break;
            }
            if ($fileType == "sql") {
                $uploadDir = 'uploads_sql/';
                $uploadFile = $uploadDir . basename($_FILES['fileToUploadSql']['name']);
            
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
            
                if (move_uploaded_file($_FILES['fileToUploadSql']['tmp_name'], $uploadFile)) {
                    $response["data"] = "File uploaded successfully.";
                    $response["status"] = "success";
                } else {
                    $response["status"] = "failed";
                    $response["data"] = "File uploaded failed.";
                }
                break;
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