<?php

use class\tools\Tools;
use provider\AppProvider;
use class\service\UserService;
use class\service\ActivityService;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");
Tools::guardAdmin("login.php");
Tools::checkIfUserGotCookieToVote();

$activityService = null;
$userService = null;
$deleteUsers = false;
$deleteActivities = false;
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
    throw new Exception("IMPOSSIBLE DE RECUPERER UN SERVICE");
}

if(isset($_POST["deleteUsers"])){
    $userService->deleteAll();
    $deleteUsers = true;
}
elseif(isset($_POST["deleteActivities"])){
    $activityService->deleteAll();
    $deleteActivities = true;
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
        "admin/nuke.css",
    ],
    "title" => "Tout supprimer"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./seeVoteActivities.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Tout supprimer.</h2>
            <div class="mainContainer">
                <p>AUCUNE CONFIRMATION LORSQU'ON CLIQUE SUR UN BOUTON</p>
                <p>Les actions effectuées ici seront irrévocables.</p>
                <p>
                    Tout sera supprimé, les utilisateurs, les activités, les votes, les images, 
                    pensez-y avant de cliquer sur un bouton.
                </p>

                <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?> method="POST">
                    <button name="deleteUsers">SUPPRIMER LES UTILISATEURS</button>
                    <button name="deleteActivities">SUPPRIMER LES ACTIVITÉS</button>
                </form>

                <?php if($deleteActivities): ?>
                    <h3>Toutes les activités ont été effacées.</h3>
                <?php elseif($deleteUsers): ?>
                    <h3>Tous les utilisateurs ont été effacés.</h3>
                <?php endif ?>
            </div>
        </div>
    </section>
</main>
<?php endHTML()?>