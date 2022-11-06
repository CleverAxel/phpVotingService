<?php

use class\tools\Tools;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");

Tools::guardAdmin("login.php");
Tools::checkIfUserGotCookieToVote();
declareHTML([
    "path" => ".././",
    "stylesheet" => [
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "admin/style.css",
        "admin/navAdmin.css",
        "admin/index.css",
    ],
    "title" => "INDEX ADMIN"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./index.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Tableau de bord</h2>
         </div>

    </section>
</main>

<?php endHTML()?>