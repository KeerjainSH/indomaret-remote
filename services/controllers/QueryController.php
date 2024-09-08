<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/scripts/sql_helper.php');

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $sql = new SQL();
            $response['method'] = 'GET';
            if (isset($_GET['id'])) {
                $user = $sql->getUser($_GET['id']);
                
                $response['message'] = "Get Data with ID: " . $_GET['id'];
                $response['data'] = $user;
            } else {
                $users = $sql->getUsers();
                $response['message'] = "Get All Data";
                $response['data'] = $users;
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
