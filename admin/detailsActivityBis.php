<?php
$errorMessage = "";

use class\tools\Tools;
use provider\AppProvider;
require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
Tools::guardAdmin("login.php");

/**
 * @var ActivityService | null
 */
$activityService = null;
try{
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = "CODE : " . $e->getCode() . " MESSAGE : " . $e->getMessage();
}

if(isset($_POST["base64"]) && $activityService != null){
    $generatedQrCode = $activityService->createQrCode($_POST["base64"]);
    $activityService->updateQrCode($generatedQrCode, $_GET["uuid"]);
}

declareHTML([
    "path" => "../",
    "stylesheet" =>[
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "admin/postActivity.css"
    ],
    "title" => "Insert db"
]);?>
<main>
    <section>
        <?php if(isset($_GET["uuid"])): ?>

                <?php
                $activity = $activityService->getByUUID($_GET["uuid"]);
                if($activity == null): ?>
                    <h3>L'UUID passé en tant que paramètre n'existe pas dans la base de donnée, vous avez filouté.</h3>
                <?php else: ?>
                    <h2>Détails de l'activité</h2>
                    <div class="detailsInsert">
                        <h3><span>Titre de l'activité</span> :</h3>
                        <h4><?php echo $activity->title ?></h4>
                        <h3><span>Résumé de l'activité : </span></h3>

                        <?php $paragraphes = explode("\n", $activity->resume);
                        foreach ($paragraphes as $paragraphe) {
                            echo "<p>${paragraphe}</p>";
                        } ?>
                        
                        <h3><span>Image principale de l'activité :</span></h3>
                        <?php if($activity->mainImg): ?>
                            <img src=<?php echo "../assets/images/".$activity->mainImg  ?> alt="">
                        <?php else: ?>
                            <h4>Aucune image séléctionnée.</h4>
                        <?php endif?>
                        
                        <h3><span>Qr code du vote pour l'activité :</span></h3>
                        <?php if($activity->qrCode): ?>
                            <img src=<?php echo "../assets/images/".$activity->qrCode  ?> alt="">
                        <?php else: ?>
                            <button class="push-button-3d" id="qrCodeButton">Générer un QR code pour le vote de cette activitée<i class="fa-solid fa-qrcode"></i></button>
                        <?php endif?>
                    </div>
                    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?uuid=".$_GET["uuid"]) ?> method="POST" name="hiddenForm">
                            <input type="hidden" name="base64">
                    </form>
                <?php endif ?>
        <?php else: ?>
            <h3>aucun uuid</h3>
        <?php endif?>
    </section>
</main>
<script>
    const SERVER_URL = <?php echo json_encode($_SERVER["SERVER_NAME"]); ?>
</script>
<script>
    const QUERY_UUID = <?php if(isset($_GET["uuid"])){echo json_encode($_GET["uuid"]); } ?>
</script>
<script src="../js/svgqrcode.js"></script>
<script src="../js/postActivity.js"></script>
<?php endHTML(); ?>
