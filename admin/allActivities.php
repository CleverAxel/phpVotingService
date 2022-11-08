<?php

/**
 * @var string | null
 */
$errorMessage = null;

/**
 * @var int | null
 */
$errorCode = null;

/**
 * @var bool | null
 */
$errorFromService = null;
$activities = null;

use class\tools\Tools;
use provider\AppProvider;
use class\service\ActivityService;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");
Tools::guardAdmin("login.php");
Tools::checkIfUserGotCookieToVote();

$activityService = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
}

try{
    if(isset($activityService)){
        $activities = $activityService->getAll();
    }
}catch(Exception $e){
    $errorFromService = true;
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
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
        "admin/allActivities.css",
    ],
    "title" => "Toutes les activités"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./allActivities.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Toutes les activités</h2>

            <div>
                <?php /*1*/ if(isset($activityService)): ?>

                    <?php /*2*/ if(isset($errorFromService) && $errorFromService == true): ?>
                        <div class="containErrorMessage">
                            <h3>Un problème est survenu au moment de récupérer toutes les activités.</h3>
                            <h4>MESSAGE D'ERREUR : <?php echo $errorMessage ?></h4>
                            <h4>CODE D'ERREUR : <?php echo $errorCode ?></h4>
                        </div>

                    <?php /*2*/ else: ?>


                        <?php /*3*/ if(isset($activities[0])): ?>
                            <div class="containActivities">
                                <?php foreach ($activities as $activity):?>
                                    <div>
                                        <h3><span>titre : </span><?php echo htmlspecialchars($activity->title) ?></h3>

                                        <div class="containerSmallInfo">
                                            <div>
                                                <i class="fa-solid fa-qrcode <?php if(!isset($activity->qrCode)){echo "_dontExist";} ?>"></i>
                                                <?php 
                                                if(isset($activity->qrCode)){
                                                    
                                                    echo '<i class="fa-solid fa-check exist"></i>';
                                                }else{
                                                    echo '<i class="fa-solid fa-slash dontExist"></i>';
                                                }
                                                ?>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-image <?php if(!isset($activity->mainImg)){echo "_dontExist";} ?>"></i>
                                                <?php 
                                                if(isset($activity->mainImg)){
                                                    
                                                    echo '<i class="fa-solid fa-check exist"></i>';
                                                }else{
                                                    echo '<i class="fa-solid fa-slash dontExist"></i>';
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="containerButton">
                                            <a href=<?php echo "./detailsActivity.php?uuid=".$activity->uuid ?> class="button"><button><i class="fa-solid fa-circle-info"></i></button></a>
                                            <a href=<?php echo "./editActivity.php?uuid=".$activity->uuid ?> class="button"><button><i class="fa-solid fa-pen"></i></button></a>
                                            <a href=<?php echo "./deleteActivity.php?uuid=".$activity->uuid ?> class="button delete"><button><i class="fa-solid fa-trash"></i></button></a>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php /*3*/ else: ?>
                            <div class="containErrorMessage">
                                <h3>Aucune activité trouvée.</h3>
                            </div>
                        <?php /*3*/ endif ?>


                    <?php /*2*/ endif ?>
                    
                <?php /*1*/ else: ?>

                    <div class="containErrorMessage">
                        <h3>Nous avons rencontré un problème au moment d'appeler un service.</h3>
                        <h4>CODE D'ERREUR : <?php echo $errorCode ?></h4>
                        <h4>MESSAGE D'ERREUR : <?php echo $errorMessage ?></h4>
                    </div>

                <?php /*1*/ endif ?>
            </div>

         </div>

    </section>
</main>
<script src="../js/admin/allActivities.js"></script>
<?php endHTML()?>