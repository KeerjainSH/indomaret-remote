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
        <div class="container">
            <h1>Upload Your File</h1>
            <form id="uploadForm" method="post" enctype="multipart/form-data">
                <label for="fileToUpload" class="file-upload-label">
                    <input type="file" id="fileToUpload" name="fileToUpload" class="file-upload-input" required>
                    Choose file
                </label>
                <button id="submit-upload" type="submit" class="upload-button">Upload</button>
            </form>
            <span id="fileName"></span>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#fileToUpload').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $('#fileName').text('Selected file: ' + fileName);
            });

            document.getElementById('submit-upload').addEventListener('click', function(e) {
                e.preventDefault();
                const fileInput = document.getElementById('fileToUpload');
                const file = fileInput.files[0];

                if (file) {
                    $('#fileName').text('Uploading please wait ...');

                    const formData = new FormData();
                    formData.append('fileToUpload', file);

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
                        $('#fileName').text('');
                    });
                } else {
                    alert('Please select a file to upload.');
                }
            });
        });
    </script>
</body>
</html>
