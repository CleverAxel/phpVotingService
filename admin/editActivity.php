<?php
$errorMessage = "";

use class\service\ActivityService;
use class\tools\Tools;
use provider\AppProvider;

require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
Tools::guardAdmin("login.php");

$activityService = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = "CODE : " . $e->getCode() . " MESSAGE : " . $e->getMessage();
}

if(isset($_POST["submit"]) && $activityService != null){

}
declareHTML([
    "path" => ".././",
    "stylesheet" => [
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "admin/editActivity.css"
    ],
    "title" => "Modifier une activité"
]); ?>
<?php
?>
<main>
    <?php if(is_null($activityService)): ?>
        <section class="failedConnectDb">
            <p>La connection à la base de donnée n'a pas pu être effectuée.</p>
            <p><?php echo $errorMessage ?></p>
        </section>

    <?php else:?>
        <section class="mainContainer">
            <?php if(isset($_GET["uuid"])): ?>

                <?php $activity = $activityService->getByUUID($_GET["uuid"]);?>
                <?php if($activity): ?>
                    <form class="formEdit" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?uuid=".$_GET["uuid"]) ?> method="POST" enctype="multipart/form-data">
                        <div>
                            <label for="title">Titre de l'activité :</label>
                        </div>
                        <div>
                            <input type="text" name="title" id="title" maxlength="255" value=<?php echo $activity->title ?>>
                        </div>

                        <div>
                            <label for="resume">Résumé de l'activité :</label>
                        </div>
                        <div>
                            <textarea name="resume" id="resume" maxlength="15000"><?php echo $activity->resume ?></textarea>
                        </div>

                        <div>
                            <label for="mainImg">Image principale :</label>
                        </div>
                        <div>
                            <input type="file" name="mainImg" id="mainImg" accept="image/*">
                        </div>

                        <div>
                            <input type="submit" value="ENVOYER" name="submit">
                        </div>
                    </form>
                <?php else: ?>
                    <h3 class="errorMsg">L'UUID n'existe pas dans la base de donnée</h3>
                <?php endif ?>

            <?php else:?>
                <h3 class="errorMsg">Aucun UUID spécifié</h3>
            <?php endif?>
        </section>
        <section class="containError">
            <?php echo $errorMessage;?>
        </section>
    <?php endif ?>
</main>
<?php endHTML(); ?>