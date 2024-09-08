<?php
define('__ROOT__', dirname(dirname(__FILE__)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret Remote</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div id="overlay" class="overlay"></div>
    <?php require_once(__ROOT__.'/views/sidebar.php');?>
    <div class="content">
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Are you sure?</h2>
                <p>This will execute the query : SELECT * FROM users;</p>
                <div id="loading-line" class="loading-container">
                    <div class="loading-line"></div>
                </div>
                <button id="modalActionBtn">Run!</button>
            </div>
        </div>
        <h2>Query Database</h2>
        <div class="query-action-section">
            <div>
                <label for="input-data">Input Data:</label>
                <input type="text" id="input-data" placeholder="Input Data">
            </div>
            <div class="button-container">
                <button id="execute-btn" class="custom-button">Execute</button>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

    <script>
        const modal = document.getElementById("myModal");
        const executeBtn = document.getElementById("execute-btn");

        // Get the <span> element that closes the modal
        const closeBtn = document.getElementsByClassName("close")[0];
        const modalActionBtn = document.getElementById("modalActionBtn");
        executeBtn.disabled = true;

        executeBtn.onclick = function() {
            const id = document.getElementById('input-data').value;
            if (id === "") {
                fetchAllData();
                return
            }
            modal.style.display = "block";
        }

        // Close the modal when the user clicks on <span> (x)
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        modalActionBtn.onclick = function() {
            const id = document.getElementById('input-data').value;
            const loadingLine = document.getElementById('loading-line');
            loadingLine.style.display = 'block';

            const overlay = document.getElementById('overlay');
            overlay.style.display = 'block';

            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'GET',
                data: { id: id},
                dataType: 'json',
                success: function(response) {
                    tableBody.innerHTML = response.data.map(user => `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                        </tr>
                    `).join('');
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    executeBtn.disabled = false;
                    loadingLine.style.display = 'none';
                    overlay.style.display = 'none';
                    modal.style.display = 'none';
                }
            });
        }


        const tableBody = document.getElementById("table-body");
        const loading = document.getElementById("loading");
        function showLoading() {
            tableBody.innerHTML = `
            <!-- Loading Spinner -->
                <tr id="loading">
                    <td colspan="3">
                        <div class="loading">
                            <div class="spinner"></div>
                        </div>
                    </td>
                </tr>
            `;
        }
        function fetchAllData() {
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'GET',
                // data: { date: date},
                dataType: 'json',
                success: function(response) {
                    tableBody.innerHTML = response.data.map(user => `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                        </tr>
                    `).join('');
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    executeBtn.disabled = false;
                }
            });
        }

        showLoading();
        fetchAllData();


    </script>
</body>
</html>
