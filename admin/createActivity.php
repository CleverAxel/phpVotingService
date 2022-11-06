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

if(isset($_POST["submit"]) && isset($activityService)){
    try{
        $activityService->insertActivityInDb();
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
        "admin/createActivity.css",
    ],
    "title" => "Ajouter une activité"
]); ?>

<main>
    <section class="boardContainer">
        <nav class="navAdmin">
            <?php navAdmin("./createActivity.php"); ?>
        </nav>
        <div class="contentBoard">
            <h2>Ajouter une activité</h2>

            <div>
                <?php if(isset($activityService)): ?>

                    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> class="formCreate" method="POST" enctype="multipart/form-data">

                        <section>
                            <div>
                                <label for="title">Titre de l'activité (*) : </label>
                            </div>
                            <div>
                                <input type="text" name="title" id="title" placeholder="Votre titre..." maxlength="255" autocomplete="off">
                            </div>
                        </section>

                        <section>
                            <div>
                                <label for="resume">Résumé de l'activité (*) : </label>
                            </div>
                            <div>
                                <textarea name="resume" id="resume"></textarea>
                            </div>
                        </section>
                        
                        <section>
                            <div class="uploadImg">
                                <label for="mainImg"> <i class="fa-solid fa-upload"></i> Ajouter une image à l'activité</label>
                                <input type="file" name="mainImg" id="mainImg" accept="image/*">

                                <div class="containFileName">
                                    <div>
                                        <i class="fa-solid fa-image"></i>
                                        <span>image_hyperlong_.png</span>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-x deleteFileIcon"></i>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <button class="submit" name="submit"><i class="fa-solid fa-file-circle-plus"></i>AJOUTER</button>
                        </section>
                    </form>

                    <?php if(isset($errorFromService) && $errorFromService == true): ?>
                        <?php Tools::errorMessage("Un problème est survenu au moment de l'ajout de l'activité.", $errorMessage) ?>
                    <?php endif?>
                    
                <?php else: ?>

                    <?php Tools::errorMessage("Nous avons rencontré un problème au moment d'appeler un service.", $errorMessage) ?>

                <?php endif ?>
            </div>

         </div>

    </section>
</main>

<script src="../js/admin/createActivity.js"></script>
<?php endHTML()?>