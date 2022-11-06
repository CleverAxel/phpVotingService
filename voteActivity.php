<?php

$errorMessage = "";

use database\db;
use class\tools\Tools;
use class\service\ActivityService;
require(__DIR__ . "/Layout/layoutHTML.php");
require(__DIR__ . "/class/service/ActivityService.php");
Tools::checkIfUserGotCookieToVote();

/**
 * @var ActivityService | null
 */
$activityService = null;
try{
    $activityService = new ActivityService(new db());
}catch(PDOException $e){
    $errorMessage = "CODE : " . $e->getCode() . " MESSAGE : " . $e->getMessage();
}

declareHTML([
    "path" => "./",
    "stylesheet" =>[
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
    ],
    "title" => "Tirage au sort"
]);
?>
<main>
    <h1>VOTE POUR CETTE ACTIVITEE</h1>
    <?php
    $activity = $activityService->getByUUID($_GET["uuid"]);
    echo "<h1>".$activity->title."</h1>"
    ?>
</main>
<?php endHTML();?>