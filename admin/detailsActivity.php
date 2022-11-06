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
$activityUUID = null;

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
    $errorCode = (int)$e->getCode();
}


try{
    if(isset($activityService) && isset($_GET["uuid"])){

        if(isset($_POST["base64"])){
            $generatedQrCode = $activityService->createQrCode($_POST["base64"]);
            $activityService->updateQrCode($generatedQrCode, $_GET["uuid"]);
        }

        $activity = $activityService->getByUUID($_GET["uuid"]);
        $activityUUID = $activity->uuid;
    }else{
        if(isset($activityService) && isset($_GET["title"])){
            $activity = $activityService->getByTitle($_GET["title"]);
            $activityUUID = $activity->uuid;
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
        <form class="formDetailsSearch" action='. htmlspecialchars($_SERVER["PHP_SELF"]). ' method="GET">
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
                            <div class="containerButton">
                                <a href=<?php echo "./editActivity.php?uuid=".$activity->uuid ?> class="button"><button><i class="fa-solid fa-pen"></i></button></a>
                                <a href=<?php echo "./deleteActivity.php?uuid=".$activity->uuid ?> class="button delete"><button><i class="fa-solid fa-trash"></i></button></a>
                            </div>
                            <div class="containerDetails">
                                    <table>
                                        <tr>
                                            <td>uuid</td>
                                            <td><?php echo htmlspecialchars($activity->uuid) ?></td>
                                        </tr>
                                        <tr>
                                            <td>titre</td>
                                            <td><?php echo htmlspecialchars($activity->title) ?></td>
                                        </tr>
                                        <tr>
                                            <td>résumé</td>
                                            <td>
                                                <?php
                                                    $paragraphes = explode("\n", $activity->resume);
                                                    foreach ($paragraphes as $paragraphe) {
                                                        echo "<p>". htmlspecialchars($paragraphe) ."</p>";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>image</td>
                                            <td>
                                                <?php
                                                if($activity->mainImg){
                                                    echo '<img src="../assets/images/'. $activity->mainImg .'" alt="image principale">';
                                                }else{
                                                    echo '<img src="../assets/images/unfound.png" alt="pas d\'image">';
                                                }
                                                ?>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Qr code pour voter</td>
                                            <td>
                                                <?php
                                                    if($activity->qrCode){
                                                        echo '<img src="../assets/images/'. $activity->qrCode .'" alt="qrCode">';
                                                    }else{
                                                        echo 
                                                        '
                                                        <button class="push-button-3d" id="qrCodeButton">Générer un QR code pour le vote de cette activité<i class="fa-solid fa-qrcode"></i></button>
                                                        <form action='.htmlspecialchars($_SERVER["PHP_SELF"]."?uuid=".$activityUUID) . ' method="POST" id="hiddenForm">
                                                            <input type="hidden" name="base64">
                                                        </form>
                                                        ';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php formSearch() ?>
                        <?php /*3*/ else: ?>
                            <?php /*4*/ if(isset($_GET["title"])): ?>
                                <div class="containerButton">
                                    <a href=<?php echo "./editActivity.php?uuid=".$activity->uuid ?> class="button"><button><i class="fa-solid fa-pen"></i></button></a>
                                    <a href=<?php echo "./deleteActivity.php?uuid=".$activity->uuid ?> class="button delete"><button><i class="fa-solid fa-trash"></i></button></a>
                                </div>
                                <div class="containerDetails">
                                    <table>
                                        <tr>
                                            <td>uuid</td>
                                            <td><?php echo htmlspecialchars($activity->uuid) ?></td>
                                        </tr>
                                        <tr>
                                            <td>titre</td>
                                            <td><?php echo htmlspecialchars($activity->title) ?></td>
                                        </tr>
                                        <tr>
                                            <td>résumé</td>
                                            <td>
                                                <?php
                                                    $paragraphes = explode("\n", $activity->resume);
                                                    foreach ($paragraphes as $paragraphe) {
                                                        echo "<p>". htmlspecialchars($paragraphe) ."</p>";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>image</td>
                                            <td>
                                                <?php
                                                if($activity->mainImg){
                                                    echo '<img src="../assets/images/'. $activity->mainImg .'" alt="image principale">';
                                                }else{
                                                    echo '<img src="../assets/images/unfound.png" alt="pas d\'image">';
                                                }
                                                ?>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Qr code pour voter</td>
                                            <td>
                                                <?php
                                                    if($activity->qrCode){
                                                        echo '<img src="../assets/images/'. $activity->qrCode .'" alt="qrCode">';
                                                    }else{
                                                        echo 
                                                        '
                                                        <button class="push-button-3d" id="qrCodeButton">Générer un QR code pour le vote de cette activité<i class="fa-solid fa-qrcode"></i></button>
                                                        <form action='.htmlspecialchars($_SERVER["PHP_SELF"]."?uuid=".$activityUUID) . ' method="POST" id="hiddenForm">
                                                            <input type="hidden" name="base64">
                                                        </form>
                                                        ';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php formSearch() ?>
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
<?php if(isset($activityUUID)): ?>
    <script>
        const SERVER_URL = <?php echo json_encode($_SERVER["SERVER_NAME"]); ?>
    </script>
    <script>
        const QUERY_UUID = <?php echo json_encode($activityUUID); ?>
    </script>
    <script src="../js/admin/svgqrcode.js"></script>
    <script src="../js/admin/createQrCode.js"></script>
<?php endif ?>
<?php endHTML()?>