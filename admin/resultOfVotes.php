<?php

use class\tools\Tools;
use provider\AppProvider;
use class\service\ActivityService;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");
Tools::guardAdmin("login.php");
Tools::checkIfUserGotCookieToVote();
$errorMessage = null;
$errorFromService = false;
$activityService = null;
$voteByActivity = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = $e->getMessage();
}

if(isset($activityService)){
    try{
        $voteByActivity = $activityService->getVoteByActivity();
    }catch(Exception $e){
        $errorFromService = true;
        $errorMessage = $e->getMessage();
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
        "admin/style.css",
        "admin/navAdmin.css",
        "admin/resultOfVotes.css",
    ],
    "title" => "Résultat des votes"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./seeVoteActivities.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Résultat des votes</h2>
            <?php if(isset($activityService)): ?>
                <div class="containerVoteDetails">

                    <div class="containChar">
                        <div class="chart"></div>
                        <div class="containNumber"></div>
                    </div>

                    <div class="containerLegend">

                    </div>
                </div>
            <?php else:?>
                <?php Tools::errorMessage("Impossible d'appeler un service.", $errorMessage) ?>
            <?php endif?>
            

        </div>
    </section>
</main>
<?php if(isset($voteByActivity)): ?>
    <script>
        const VOTES = <?php echo json_encode($voteByActivity) ?>
    </script>
    <script src="../js/admin/resultOfVotes.js"></script>
<?php endif ?>
<?php endHTML()?>