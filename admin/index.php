<?php

use objectClass\tools\Tools;
use provider\AppProvider;
use objectClass\service\UserService;
use objectClass\service\ActivityService;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");

Tools::guardAdmin("login.php");
Tools::checkIfUserGotCookieToVote();
$errorFromService = false;
$errorMessage = null;
$activityService = null;
$userService = null;

$countUser = null;
$countActivity = null;
$nbrVotesByUser = null;
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

if(isset($userService) && isset($activityService)){
    try{
        $countUser = $userService->getCountUser();
        $countActivity = $activityService->getCountActivity();
        $nbrVotesByUser = $userService->getNumberOfVotesByUser();
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
            <?php if(isset($userService) && isset($activityService)): ?>
                <?php if(!$errorFromService): ?>
                    <div class="resumeInfo">
                        <h3> <i class="fa-solid fa-user"></i>Nombre d'utilisateurs enregistrés : <span><?php echo $countUser ?></span></h3>
                        <h3> <i class="fa-solid fa-folder-open"></i>Nombre d'activités créées : <span><?php echo $countActivity ?></span></h3>
                        <h3><i class="fa-solid fa-check-to-slot"></i>Moyenne de votes par utilisateur : <span><?php if($nbrVotesByUser != null){ echo $nbrVotesByUser;}else{echo "0";} ?></span></h3>
                    </div>
                <?php else:?>
                    <?php Tools::errorMessage("Un service a eu un problème.", $errorMessage) ?>
                <?php endif?>
            <?php else: ?>
                <?php Tools::errorMessage("Nous n'avons pas pu appeler un service", $errorMessage) ?>
            <?php endif ?>
         </div>

    </section>
</main>

<?php endHTML()?>