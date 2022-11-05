<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test2</title>
</head>
<body>
    <?php

use provider\AppProvider;

    require(__DIR__ . "/provider/AppProvider.php");

    AppProvider::getInstance()->make("db");
    // $handle = fopen("textFile.txt", "x");
    // fwrite($handle, "lol");
    // fclose($handle);

    
    // echo "<pre>";
    // print_r($_SERVER);
    // echo "</pre>"
    ?>

    
</body>
</html>