<?php
function declareHTML($args = []){
    echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" type="image/x-icon" href="'. $args["path"] . "assets/images/vote.ico" .'">';
            
        foreach ($args["stylesheet"] as $css) {
            echo '<link rel="stylesheet" href="'. $args["path"] . "CSS/" . $css .'">';
        }
        echo '
            <title>'.$args["title"].'</title>
        </head>
        <body>
            <header>
                <div>
                    <a href="' . $args["path"] . "index.php" . '"><h1>Votez !</h1></a>
                </div>
            </header>
    ';
}

function endHTML(){
    echo '
    </body>
    </html>
    ';
}
?>
    

