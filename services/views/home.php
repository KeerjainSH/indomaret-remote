<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/controllers/DateController.php');
require_once(__ROOT__.'/scripts/ssh_helper.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret Remote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
</head>
<body>
    <?php require_once(__ROOT__.'/views/sidebar.php');?>
    <div class="content">
        <h1>Welcome to Indomaret Remote</h1>
        <div class="date">
            <div>
                <label for="date-picker">Select Date:</label>
                <input type="text" id="date-picker" placeholder="Select Date">
            </div>
            <div>
                <label for="current-server-date">Current Server Date:</label>
                <input disabled type="text" id="server-date" placeholder="23/08/2024">
            </div>
        </div>
        <p>This is a simple page with a sidebar navigation menu.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('#date-picker', {
            onChange: async function(selectedDates, dateStr, instance) {
                console.log(dateStr);
                document.getElementById("date-picker").disabled = true;
            }
        });

        <?php 
            $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');
            $output = $ssh->executeCommand('powershell Get-Date -Format "dd/MM/yyyy"');
        ?>

        document.getElementById("server-date").value = <?php echo $output?>
    </script>
    <?php 
        $ssh = SSHConnection::getInstance('4.145.89.179', 22, 'eka_rahadi');
        $output = $ssh->executeCommand('dir');
    ?>
</body>
</html>
