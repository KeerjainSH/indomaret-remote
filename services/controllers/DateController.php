<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/scripts/ssh_helper.php');

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $response['method'] = 'GET';
            $response['message'] = "Test";

            $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');
            $output = $ssh->executeCommand('powershell Get-Date -Format "dd/MM/yyyy"');

            $response["data"] = $output;
            break;
        
        case 'POST':
            $response['success'] = true;
            $response['message'] = "success";

            $postData = $_POST;

            // Include the data in the response
            if (!empty($postData)) {
                $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');
                $output = $ssh->executeCommand('powershell Set-Date -Date '.$postData['date']);
                $response['data'] = $output;
            } else {
                $response['data'] = "No data sent";
            }
            break;

        // case 'PUT':
        //     parse_str(file_get_contents("php://input"), $putData);
        //     $response['method'] = 'PUT';
        //     $response['message'] = "PUT request received";
        //     $response['data'] = $putData; // Any PUT data sent
        //     break;

        // case 'DELETE':
        //     $response['method'] = 'DELETE';
        //     $response['message'] = "DELETE request received";
        //     break;

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

?>
