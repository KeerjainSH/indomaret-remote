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
                <p id="modal-text-info">This will execute the query : SELECT * FROM users;</p>
                <div id="loading-line" class="loading-container">
                    <div class="loading-line"></div>
                </div>
                <button id="modalActionBtn">Run!</button>
            </div>
        </div>
        <h2>Query Database</h2>
        <div class="query-action-section">
            <!-- <div>
                <label for="input-data">Input Data:</label>
                <input type="text" id="input-data" placeholder="Input Data">
            </div> -->
            <div class="button-container">
                <button id="select-btn" class="custom-button">Select Query</button>
            </div>
            <div class="button-container">
                <button id="update-const-btn" class="custom-button">Update `const` Query</button>
            </div>
            <div class="button-container">
                <button id="delete-const-btn" class="custom-button">Delete `const` Query</button>
            </div>
            <div class="button-container">
                <button id="update-stmast-btn" class="custom-button">Update `stmast` Query</button>
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
        const textInfo = document.getElementById("modal-text-info");
        const modal = document.getElementById("myModal");
        const selectBtn = document.getElementById("select-btn");
        const updateConstBtn = document.getElementById("update-const-btn");
        const deleteConstBtn = document.getElementById("delete-const-btn");
        const updateStmastBtn = document.getElementById("update-stmast-btn");

        // Get the <span> element that closes the modal
        const closeBtn = document.getElementsByClassName("close")[0];
        const modalActionBtn = document.getElementById("modalActionBtn");

        selectBtn.onclick = function() {
            textInfo.textContent = "This will execute the query : SELECT * FROM toko;"
            // set data button selected
            $('#modal-text-info').attr('data-btn-type', 'select-btn');
            modal.style.display = "block";
        }
        updateConstBtn.onclick = function() {
            textInfo.textContent = "This will execute the query : UPDATE const SET jenis = 'N' WHERE rkey in ('wsb', 'ne', 'pco');"
            // set data button selected
            $('#modal-text-info').attr('data-btn-type', 'update-const-btn');
            modal.style.display = "block";
        }
        deleteConstBtn.onclick = function() {
            textInfo.textContent = "This will execute the query : DELETE const WHERE rkey='ccd';"
            // set data button selected
            $('#modal-text-info').attr('data-btn-type', 'delete-const-btn');
            modal.style.display = "block";
        }
        updateStmastBtn.onclick = function() {
            textInfo.textContent = "This will execute the query : UPDATE stmast SET begbal='1000', qty='1000';"
            // set data button selected
            $('#modal-text-info').attr('data-btn-type', 'update-stmast-btn');
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
            const btnType = $('#modal-text-info').attr("data-btn-type");
            console.log(btnType);
            return;

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
                    selectBtn.disabled = false;
                }
            });
        }

        showLoading();
        fetchAllData();


    </script>
</body>
</html>
