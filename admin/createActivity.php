<?php
$errorMessage = "";

use class\service\ActivityService;
use database\db;
require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../database/db.php");
require(__DIR__ . "/../class/service/ActivityService.php");



/**
 * @var ActivityService | null
 */
$activityService = null;
try{
    $activityService = new ActivityService(new db());
}catch(PDOException $e){
    $errorMessage = "CODE : " . $e->getCode() . " MESSAGE : " . $e->getMessage();
}

if(isset($_POST["submit"]) && $activityService != null){
    try{
        $activityService->insertActivityInDb();
    }catch(Exception $e){
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
        "admin/createActivity.css"
    ],
    "title" => "Créer une activité"
]); ?>
<main>
    <?php if(is_null($activityService)): ?>
        <section class="failedConnectDb">
            <p>La connection à la base de donnée n'a pas pu être effectuée.</p>
            <p><?php echo $errorMessage ?></p>
        </section>

    <?php else:?>
        <section class="containerForm">
            <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> method="POST" enctype="multipart/form-data">
                <div>
                    <label for="title">Titre de l'activité :</label>
                </div>
                <div>
                    <input type="text" name="title" id="title" maxlength="255">
                </div>

                <div>
                    <label for="resume">Résumé de l'activité :</label>
                </div>
                <div>
                    <textarea name="resume" id="resume" maxlength="15000"></textarea>
                </div>

                <!-- <div>
                    <label for="qrCode">Qr code :</label>
                </div>
                <div>
                    <input type="file" name="qrCode" id="qrCode" accept="image/*">
                </div> -->

                <div>
                    <label for="mainImg">Image principale à afficher :</label>
                </div>
                <div>
                    <input type="file" name="mainImg" id="mainImg" accept="image/*">
                </div>

                <div>
                    <input type="submit" value="ENVOYER" name="submit">
                </div>
            </form>
        </section>
        <section class="containError">
            <?php
                echo $errorMessage;
            ?>
        </section>
    <?php endif ?>
</main>
<?php endHTML(); ?>