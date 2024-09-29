<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/scripts/sql_helper.php');

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $sql = new SQL();
            $response['method'] = 'GET';
            
            $users = $sql->getAllToko();
            $response['message'] = "Get All Data";
            $response['data'] = $users;

            break;
        case 'POST':
            $sql = new SQL();
            $response['method'] = 'POST';

            $postData = $_POST;
            $queryType = $postData['type'];

            if ($queryType == "const") {
                $res = $sql->updateConst();
                $response['message'] = "Update const table";
                $response['data'] = $res;
            } else {
                $res = $sql->updateStmast();
                $response['message'] = "Update stmast table";
                $response['data'] = $res;
            }
            break;
        case 'DELETE':
            $response['method'] = 'DELETE';
            $sql = new SQL();
            $res = $sql->deleteConst();
            $response['message'] = "Delete const table";
            $response['data'] = $res;
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
