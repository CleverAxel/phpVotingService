<?php

/**
 * @var string | null
 */
$errorMessage = null;

/**
 * @var string | null
 */
$errorUpdate = null;

/**
 * @var bool | null
 */
$errorFromUpdate = false;

/**
 * @var bool
 */
$updateDone = false;

$activity = null;
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
}

if((isset($_POST["submitFom"]) || isset($_POST["title"])) && isset($activityService)){
    try{
        $activityService->updateActivity();
        $updateDone = true;
    }catch(Exception $e){
        $errorMessage = $e->getMessage();
        $errorFromUpdate = true;
    }
}

try{
    if(isset($activityService) && isset($_GET["uuid"])){
        $activity = $activityService->getByUUID($_GET["uuid"]);
    }
}catch(Exception $e){
    $errorMessage = $e->getMessage();
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
        "admin/editActivity.css",
    ],
    "title" => "Modifier une activité"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin(""); ?>
        </nav>
        <div class="contentBoard">
            <h2>Modifier l'activité</h2>

            <div>
                <?php /*1*/ if(isset($activityService)): ?>

                    <?php /*2*/if(isset($_GET["uuid"])): ?>

                        <?php /*3*/ if(isset($activity)): ?>
                            <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?uuid=".$_GET["uuid"]) ?> name="formEdit" class="formEdit" method="POST" enctype="multipart/form-data">
                                <section>
                                    <div>
                                        <label for="title">Titre de l'activité (*) : </label>
                                    </div>
                                    <div>
                                        <input type="text" name="title" id="title" placeholder="Votre titre..." maxlength="255" autocomplete="off" value="<?php echo htmlspecialchars($activity->title) ?>">
                                    </div>
                                </section>

                                <section>
                                    <div>
                                        <label for="resume">Résumé de l'activité (*) : </label>
                                    </div>
                                    <div>
                                        <textarea name="resume" id="resume"><?php echo htmlspecialchars($activity->resume) ?></textarea>
                                    </div>
                                </section>
                                
                                <section>
                                    <div class="uploadImg">
                                        <label for="mainImg"> <i class="fa-solid fa-upload"></i> Modifier l'image de l'activité</label>
                                        <input type="file" name="mainImg" id="mainImg" accept="image/*">
                                        <input type="hidden" value="0" id="needToDeleteImg" name="needToDeleteImg">
                                        <input type="hidden" value=<?php echo $_GET["uuid"] ?> id="uuid" name="uuid">
                                        <div class="containFileName" style="display: none;">
                                            <div>
                                                <i class="fa-solid fa-image"></i>
                                                <span><?php echo htmlspecialchars($activity->mainImg) ?></span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-x deleteFileIcon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <?php if(!isset($activity->qrCode)): ?>
                                <section>
                                    <div class="buttonRadioQrCode">
                                        <h3>Ajouter un QR code pour le vote de cette activité ?</h3>
                                        <div>
                                            <input type="radio" name="addQrCode" id="yes" value="1">
                                            <label for="yes" class="yes" >oui</label>
                                            <input type="radio" name="addQrCode" id="no" checked="checked" value="0">
                                            <label for="no" class="no">non</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="base64" id="base64">
                                </section>
                                <?php endif ?>
                                <section>
                                    <button class="submit" name="submitFom" id="submitFom"><i class="fa-solid fa-pen"></i>MODIFIER</button>
                                </section>
                            </form>
                            <?php 
                            if($errorFromUpdate == true){
                                Tools::errorMessage("Un problème est survenu lors de la modification", $errorMessage);
                            }else if($updateDone == true){
                                echo '<div class="updateDone"><h3>Cette activité a été mise à jour.</h3></div>';
                            }
                            ?>
                        <?php /*3*/ else: ?>
                            <?php Tools::errorMessage("Un problème est survenu au moment de récupérer les informations.", $errorMessage) ?>
                        <?php /*3*/ endif ?>
                    <?php /*2*/else: ?>
                        <?php Tools::errorMessage("Impossible de récupérer les détails", "Aucun uuid passé dans l'URL") ?>
                    <?php /*2*/endif ?>
                <?php /*1*/ else: ?>

                    <?php Tools::errorMessage("Nous avons rencontré un problème au moment d'appeler un service.", $errorMessage) ?>

                <?php /*1*/ endif ?>
            </div>

         </div>

    </section>
</main>

<?php if(isset($_GET["uuid"]) && isset($activityService) && !isset($activity->qrCode)):?>
    <script src="../js/admin/svgqrcode.js"></script>
<?php endif ?>
<script>
    const SERVER_URL = <?php echo json_encode($_SERVER["SERVER_NAME"]); ?>
</script>
<script>
    const QUERY_UUID = <?php echo json_encode($_GET["uuid"]); ?>
</script>
<script src="../js/admin/editActivity.js"></script>
<?php endHTML()?>