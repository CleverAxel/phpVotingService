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
$activity = null;

use class\tools\Tools;
use provider\AppProvider;
use class\service\ActivityService;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
require(__DIR__ . "/navAdmin.php");
Tools::guardAdmin("login.php");

$activityService = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = $e->getMessage();
    $errorCode = (int)$e->getCode();
}


try{
    if(isset($activityService) && isset($_GET["uuid"])){
        $activity = $activityService->getByUUID($_GET["uuid"]);
    }else{
        if(isset($activityService) && isset($_GET["title"])){
            $activity = $activityService->getByTitle($_GET["title"]);
        }
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
        "admin/detailsActivity.css",
    ],
    "title" => "Détails de l'activité"
]); ?>

<?php

function formSearch(){
    echo 
    '
    <div class="containerForm">
        <form class="formDetails" action='. htmlspecialchars($_SERVER["PHP_SELF"]). ' method="GET">
            <div>
                <label for="title">Rechercher une activité par titre</label>
            </div>
            <div>
                <input type="text" id="title" name="title">
            </div>
            <div>
                <button class="submit"><i class="fa-solid fa-magnifying-glass"></i>Chercher</button>
            </div>
        </form>
    </div>
    ';
}

/**
 * Petite documentation car c'est le bordel avec les IF.
 * 
 * (1)Si nous n'arrivons pas à établir une connexion avec la BDD, on abandonne tout.
 * (2)Si un UUID ou un titre a été passé en tant que query paramater mais que la base de donnée
 * n'a rien retrouvé, message d'erreur, et je donne un formulaire pour refaire une recherche par titre.
 * (3)Si la condition numéro 2 a été passée on regarde si c'est un UUID qui a été passé en paramètre, si c'est le cas
 * on affiche les infos SINON on regarde si c'est un titre qui a été passé en paramètre.
 * (4)Si un titre a été passé en paramètre on affiche les infos sinon on donne un formulaire pour rechercher par titre.
 */
?>
<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./detailsActivity.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Détails de l'activité</h2>

            <div>
                <?php /*1*/ if(isset($activityService)): ?>

                    <?php /*2*/ if(isset($errorFromService) && $errorFromService == true): ?>
                        
                        <?php Tools::errorMessage("Un problème est survenu au moment de récupérer les informations.", $errorMessage) ?>
                        <?php formSearch() ?>

                    <?php /*2*/ else: ?>
                        <?php /*3*/ if(isset($_GET["uuid"])): ?>
                            <div class="containerDetails">
                                <h3><span>titre :</span>test</h3>
                            </div>
                        <?php /*3*/ else: ?>
                            <?php /*4*/ if(isset($_GET["title"])): ?>
                                <div class="containerDetails">
                                    <h3><span>titre : </span>test</h3>
                                    <h3><span>resumé : </span>blablabla</h3>
                                </div>
                            <?php /*4*/ else: ?>
                                <?php Tools::errorMessage("Impossible de récupérer les détails", "Aucun uuid passé dans l'URL") ?>
                                <?php formSearch() ?>
                            <?php /*4*/ endif?>
                        <?php /*3*/ endif?>
                    <?php /*2*/ endif ?>
                    
                <?php /*1*/ else: ?>

                    <?php Tools::errorMessage("Nous avons rencontré un problème au moment d'appeler un service.", $errorMessage) ?>

                <?php /*1*/ endif ?>
            </div>

         </div>

    </section>
</main>
<script src="../js/allActivities.js"></script>
<?php endHTML()?>