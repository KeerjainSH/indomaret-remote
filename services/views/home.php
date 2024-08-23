<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/controllers/DateController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret Remote</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
</head>
<body>
    <?php require_once(__ROOT__.'/views/sidebar.php');?>
    <div class="content">
        <h1>Welcome to My Website</h1>
        <p>This is a simple page with a sidebar navigation menu.</p>
    </div>
</body>
</html>
