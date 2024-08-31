<?php
define('__ROOT__', dirname(dirname(__FILE__)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret Remote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php require_once(__ROOT__.'/views/sidebar.php');?>
    <div class="content">
        <h1>Welcome to Indomaret Remote</h1>
        <div class="date">
            <div>
                <h2>Modify Server Date</h2>
                <label for="date-picker">Select Date:</label>
                <input type="text" id="date-picker" placeholder="Select Date">
            </div>
            <div class="button-container">
                <button id="change-date" class="custom-button">Change</button>
            </div>
            <div class="server-date-container">
                <label for="current-server-date">Current Server Date:</label>
                <input disabled type="text" id="server-date" placeholder="Fetching Current Date ...">
            </div>
        </div>
        <div class="date check-app">
            <div>
                <h2>Check Application Status</h2>
                <label for="app-check">Enter App Name:</label>
                <input type="text" id="app-name" placeholder="Enter App Name to check">
            </div>
            <div class="button-container">
                <button id="check-app" class="custom-button">Check</button>
            </div>
            <div class="button-container">
                <button id="open-app" class="custom-button-open">Open App</button>
            </div>
            <div class="status-container"><span id="app-status"></span></div>
        </div>
        <!-- <p>This is a simple page with a sidebar navigation menu.</p> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            $("#open-app").click(function(){
                document.getElementById("open-app").disabled = true;
                document.getElementById("app-status").textContent = "Starting the app, please wait ...";
                const appName = $('#app-name').val();
                if (appName === "") {
                    alert("Enter App name!")
                    return;
                }

                $.ajax({
                    url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>AppController.php',
                    method: 'POST',
                    data: { appName: appName},
                    dataType: 'json',
                    success: function(response) {
                        const regex = /SUCCESS/;
                        const containsSuccess = regex.test(response.data);
                        if (containsSuccess) {
                            document.getElementById("app-status").textContent = "App is Open";
                            return;
                        }
                        document.getElementById("app-status").textContent = "App Not Open";
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + status + " - " + error);
                    },
                    complete: function(xhr, status) {
                        document.getElementById("open-app").disabled = false;
                    }
                });
            }); 
            $("#check-app").click(function(){
                document.getElementById("check-app").disabled = true;
                document.getElementById("app-status").textContent = "Checking ...";
                const appName = $('#app-name').val();

                if (appName === "") {
                    alert("Enter App name!")
                    return;
                }

                $.ajax({
                    url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>AppController.php',
                    method: 'GET',
                    data: { appName: appName},
                    dataType: 'json',
                    success: function(response) {
                        const data = response.data
                        if (data === null) {
                            document.getElementById("app-status").textContent = "App Not Open";
                            return;
                        }
                        document.getElementById("app-status").textContent = "App is Open";
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + status + " - " + error);
                    },
                    complete: function(xhr, status) {
                        document.getElementById("check-app").disabled = false;
                    }
                });
            }); 
        });
        document.getElementById('change-date').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById("date-picker").disabled = true;
            const date = document.getElementById('date-picker').value;
            console.log(date);
            updateServerDate(date);

        });

        flatpickr('#date-picker', {
            enableTime: true,
            onChange: async function(selectedDates, dateStr, instance) {
            }
        });

        function updateServerDate(date) {
            document.getElementById("server-date").value = "Fetching Current Date ...";
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>DateController.php',
                method: 'POST',
                data: { date: date},
                dataType: 'json',
                success: function(response) {
                    fetchCurrentDate();
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    document.getElementById("date-picker").disabled = false;
                }
            });
        }

        function fetchCurrentDate() {
            document.getElementById("server-date").value = "Fetching Current Date ...";
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>DateController.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let dateString = response.data;

                    // Remove extra characters like \r\n
                    dateString = dateString.trim();
                    document.getElementById("server-date").value = dateString;
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                }
            });
        }

        fetchCurrentDate();
    </script>
</body>
</html>
