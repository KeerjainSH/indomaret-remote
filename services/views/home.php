<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/controllers/DateController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World in PHP</title>
</head>
<body>
    <h1> <?php echo hello_world(); ?></h1>
</body>
</html>
