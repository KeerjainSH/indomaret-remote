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
    <?php require_once(__ROOT__.'/views/sidebar.php');?>
    <div class="content">
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Are you sure?</h2>
                <p>This will execute the query : SELECT * FROM users;</p>
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
            alert("Button inside the modal clicked!");
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
        function fetchData() {
            setTimeout(function() {
                executeBtn.disabled = false;
                const data = [
                    { id: 1, email: 'john.doe@example.com', name: 'John Doe' },
                    { id: 2, email: 'jane.smith@example.com', name: 'Jane Smith' }
                ];

                tableBody.innerHTML = data.map(user => `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.email}</td>
                        <td>${user.name}</td>
                    </tr>
                `).join('');
            }, 3000);
        }
        showLoading();
        fetchData();


    </script>
</body>
</html>
