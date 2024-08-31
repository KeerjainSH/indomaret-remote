<?php 
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/scripts/ssh_helper.php');

function handle_request() {
    $response = array();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $response['method'] = 'GET';
            $response['message'] = "Check APP";
            $response['data'] = null;

            function output($str, &$response) {
                if (!isset($response["data"])) {
                    $response["data"] = $str;
                    flush();
                    ob_flush();
                }
            }

            $appName = $_GET['appName'];

            $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');
            $ssh->executeCommandWithOutput("powershell -Command \"Get-Process -Name " .$appName." -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Id\"",
            function($str) use (&$response) {
                output($str, $response);
            });

            break;
        
        case 'POST':
            function outputPOST($str, &$response) {
                if (!isset($response["data"])) {
                    $response["data"] = $str;
                    flush();
                    ob_flush();
                }
            }
            $response['method'] = 'POST';
            $response['message'] = "Open APP";

            $postData = $_POST;

            // Include the data in the response
            if (!empty($postData)) {
                $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');

                // // Check scheduled task
                // $ssh->executeCommandWithOutput('schtasks /query /fo list /v | findstr /i "TaskName.*'.$postData['appName'].'-sched"',
                // function($str) use (&$response) {
                //     outputPOST($str, $response);
                // });

                // Create Task First
                $ssh->executeCommandWithOutput('schtasks /create /tn '.$postData['appName'].'-sched'.' /tr '.$postData['appName'].'.exe /sc onstart /f',
                    function($str) use (&$response) {
                        outputPOST($str, $response);
                });
                $response["data"] = null;

                //Run task 
                $ssh->executeCommandWithOutput('schtasks /run /tn '.$postData['appName'].'-sched',
                    function($str) use (&$response) {
                        outputPOST($str, $response);
                });
            } else {
                $response['data'] = "No data sent";
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
?>