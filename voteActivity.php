<?php

$errorMessage = "";

use class\tools\Tools;
use provider\AppProvider;
use class\service\UserService;
use class\service\ActivityService;
require(__DIR__ . "/Layout/layoutHTML.php");
require(__DIR__ . "/provider/AppProvider.php");
Tools::checkIfUserGotCookieToVote();
$activityService = null;
$userService = null;
$activity = null;
try{

    $db = AppProvider::getInstance()->make("db");
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService", [$db]);
    
    /**
     * @var UserService | null
     */
    $userService = AppProvider::getInstance()->make("userService", [$db]);
}catch(PDOException $e){
    $errorMessage = $e->getMessage();
}

try{
    if(isset($_GET["uuid"]) && isset($activityService)){
        $activity = $activityService->getByUUID($_GET["uuid"]);
    }
}catch(Exception $e){
    $errorMessage = $e->getMessage();
}

try{
    $userService->checkIfUserExistsInDBElseCreateIt();
}catch(Exception $e){
    $errorMessage = $e->getMessage();
}

declareHTML([
    "path" => "./",
    "stylesheet" =>[
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "voteActivity.css",
    ],
    "title" => "Voter pour cette activité"
]);
?>
<main>
    <section>
        <h2>Voter pour cette activité ?</h2>
        <?php if(isset($activityService) && isset($userService)): ?>
            <?php if(isset($_GET["uuid"])): ?>
                <?php if(isset($activity)): ?>

                    <div class="cardActivity">
                        <div class="imgContainer">
                            <?php if(isset($activity->mainImg)):?>
                                <img src="<?php echo "./assets/images/".htmlspecialchars($activity->mainImg) ?>" alt="Image de l'activité">
                            <?php else:?>
                                <img src="./assets/images/unfound.png" alt="Aucune image pour cette activité.">
                            <?php endif?>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($activity->title) ?></h3>

                        <div class="containerButton">
                            <a href="./confirmVote.php?uuid=<?php echo $activity->uuid?>" class="button"><button><i class="fa-solid fa-check"></i></button></a>
                            <a href="./allActivities.php" class="button"><button><i class="fa-solid fa-xmark"></i></button></a>
                        </div>

                    </div>

                <?php else: ?>
                <?php Tools::errorMessage("Impossible de récupérer l'activité.", $errorMessage)?>
                <?php endif ?>
            <?php else:?>
            <?php Tools::errorMessage("Impossible de récupérer l'activité.", "Aucun UUID passé par l'URL.")?>
            <?php endif ?>
        <?php else: ?>
            <?php Tools::errorMessage("Nous n'avons pas pu appeler un service.", $errorMessage) ?>
        <?php endif?>
    </section>
</main>
<?php endHTML();?>