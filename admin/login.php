<?php

use class\tools\Tools;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");

if(Tools::checkIfCookieExist("iamanadmin")){
    Tools::redirect("index.php");
}

if(isset($_POST["submit"])){
    if($_POST["password"] == "test1234="){
        Tools::setCookieForAWeek("iamanadmin", "foo");
        Tools::redirect("index.php");
    }
}

declareHTML([
    "path" => ".././",
    "stylesheet" => [
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "admin/login.css"
    ],
    "title" => "Créer une activité"
]); ?>

<main>
    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> method="POST">
        <div>
            <label for="password">MOT DE PASSE</label>
        </div>
        <div>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <input type="submit" value="LOGIN" name="submit">
        </div>
    </form>
</main>

<?php
endHTML();
?>