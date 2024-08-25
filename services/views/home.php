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
                <label for="date-picker">Select Date:</label>
                <input type="text" id="date-picker" placeholder="Select Date">
            </div>
            <div>
                <label for="current-server-date">Current Server Date:</label>
                <input disabled type="text" id="server-date" placeholder="Fetching Current Date ...">
            </div>
        </div>
        <p>This is a simple page with a sidebar navigation menu.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('#date-picker', {
            onChange: async function(selectedDates, dateStr, instance) {
                document.getElementById("date-picker").disabled = true;
                updateServerDate(dateStr);
            }
        });

        function updateServerDate(date) {
            let dateParts = date.split("-");
            let newDate = `${dateParts[1]}/${dateParts[2]}/${dateParts[0]}`;

            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>DateController.php',
                method: 'POST',
                data: { date: newDate},
                dataType: 'json',
                success: function(response) {
                    fetchCurrentDate();
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                }
            });
        }

        function fetchCurrentDate() {
            document.getElementById("server-date").value = "Fetching Current Date ...";
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>DateController.php',
                method: 'GET',
                // data: { name: 'John Doe', email: 'john.doe@example.com' }, // Data sent in POST request
                dataType: 'json',
                success: function(response) {
                    let dateString = response.data;

                    // Remove extra characters like \r\n
                    dateString = dateString.trim();
                    const [day, month, year] = dateString.split("/");

                    const formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
                    document.getElementById("server-date").value = formattedDate;
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
