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
            <form action="/upload" method="post" enctype="multipart/form-data">
                <label for="fileUpload" class="file-upload-label">
                    <input type="file" id="fileUpload" name="fileUpload" class="file-upload-input" multiple required>
                    Choose file
                </label>
                <button type="submit" class="upload-button">Upload</button>
            </form>
        </div>
    </div>
</body>
</html>
