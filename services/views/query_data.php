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
                    <th>RECID1</th>
                    <th>RECID</th>
                    <th>KDSTRATA</th>
                    <th>KDORGAN</th>
                    <th>KDLOC</th>
                    <th>KDTK</th>
                    <th>nama</th>
                    <th>ALMT</th>
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

            const loadingLine = document.getElementById('loading-line');
            loadingLine.style.display = 'block';

            const overlay = document.getElementById('overlay');
            overlay.style.display = 'block';

            if (btnType === "select-btn") {
                fetchAllData();
                return;
            }
            if (btnType === "update-const-btn") {
                updateConstStmt()
                return;
            }
            if (btnType === "delete-const-btn") {
                deleteConstStmt();
                return;
            }
            if (btnType === "update-stmast-btn") {
                updateStmastStmt()
                return;
            }
        }


        const tableBody = document.getElementById("table-body");
        const loading = document.getElementById("loading");
        function showLoading() {
            tableBody.innerHTML = `
            <!-- Loading Spinner -->
                <tr id="loading">
                    <td colspan="8">
                        <div class="loading">
                            <div class="spinner"></div>
                        </div>
                    </td>
                </tr>
            `;
        }
        function cleanup() {
            const loadingLine = document.getElementById('loading-line');
            const overlay = document.getElementById('overlay');

            loadingLine.style.display = 'none';
            overlay.style.display = 'none';
            modal.style.display = 'none';
        }

        function deleteConstStmt() {
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    console.log("const delete", response);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    cleanup();
                }
            });
        }

        function updateStmastStmt() {
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'POST',
                data: { type: 'stmast'},
                dataType: 'json',
                success: function(response) {
                    console.log("stmast post", response);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    cleanup()
                }
            });
        }

        function updateConstStmt() {
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'POST',
                data: { type: 'const'},
                dataType: 'json',
                success: function(response) {
                    console.log("const post", response);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    cleanup();
                }
            });
        }
        function fetchAllData() {
            $.ajax({
                url: '<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>QueryController.php',
                method: 'GET',
                // data: { date: date},
                dataType: 'json',
                success: function(response) {
                    tableBody.innerHTML = response.data.map(datum => `
                        <tr>
                            <td>${datum.RECID1}</td>
                            <td>${datum.RECID}</td>
                            <td>${datum.KDSTRATA}</td>
                            <td>${datum.KDORGAN}</td>
                            <td>${datum.KDLOC}</td>
                            <td>${datum.KDTK}</td>
                            <td>${datum.nama}</td>
                            <td>${datum.ALMT}</td>
                        </tr>
                    `).join('');
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " - " + error);
                },
                complete: function(xhr, status) {
                    cleanup();
                }
            });
        }

        // showLoading();

    </script>
</body>
</html>
