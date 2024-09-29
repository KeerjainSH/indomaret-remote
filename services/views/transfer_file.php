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
        <h2>Transfer File</h2>
        <div class="box">
            <div class="container">
                <h1>Upload Your CSV File</h1>
                <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <label for="fileToUploadCsv" class="file-upload-label">
                        <input type="file" id="fileToUploadCsv" name="fileToUploadCsv" class="file-upload-input" required>
                        Choose file
                    </label>
                    <button id="submit-upload-csv" type="submit" class="upload-button">Upload</button>
                </form>
                <span id="fileNameCsv"></span>
            </div>
            <div class="container">
                <h1>Upload Your SQL File</h1>
                <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <label for="fileToUploadSql" class="file-upload-label">
                        <input type="file" id="fileToUploadSql" name="fileToUploadSql" class="file-upload-input" required>
                        Choose file
                    </label>
                    <button id="submit-upload-sql" type="submit" class="upload-button">Upload</button>
                </form>
                <span id="fileNameSql"></span>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#fileToUploadCsv').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $('#fileNameCsv').text('Selected file: ' + fileName);
            });
            $('#fileToUploadSql').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $('#fileNameSql').text('Selected file: ' + fileName);
            });

            document.getElementById('submit-upload-csv').addEventListener('click', function(e) {
                e.preventDefault();
                const fileInput = document.getElementById('fileToUploadCsv');
                const file = fileInput.files[0];

                if (file) {
                    $('#fileNameCsv').text('Uploading please wait ...');

                    const formData = new FormData();
                    formData.append('fileToUploadCsv', file);
                    formData.append('type', 'csv');

                    fetch('<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>FileTransferController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.status === "success") {
                            alert(result.data)
                        } else {
                            alert(result.data)
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    }).finally(() => {
                        $('#fileNameCsv').text('');
                    });
                } else {
                    alert('Please select a file to upload.');
                }
            });

            document.getElementById('submit-upload-sql').addEventListener('click', function(e) {
                e.preventDefault();
                const fileInput = document.getElementById('fileToUploadSql');
                const file = fileInput.files[0];

                if (file) {
                    $('#fileNameSql').text('Uploading please wait ...');

                    const formData = new FormData();
                    formData.append('fileToUploadSql', file);
                    formData.append('type', 'sql');

                    fetch('<?php echo "http://" .$_SERVER['SERVER_NAME']."/indomaret-remote/services/controllers/";?>FileTransferController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.status === "success") {
                            alert(result.data)
                        } else {
                            alert(result.data)
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    }).finally(() => {
                        $('#fileNameSql').text('');
                    });
                } else {
                    alert('Please select a file to upload.');
                }
            });
        });
    </script>
</body>
</html>
