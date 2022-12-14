<?php
$errorMessage = null;

use objectClass\tools\Tools;
use provider\AppProvider;
use objectClass\service\ActivityService;

require(__DIR__ . "/Layout/layoutHTML.php");
require(__DIR__ . "/provider/AppProvider.php");
Tools::checkIfUserGotCookieToVote();

$activities = null;
$activityService = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = $e->getMessage();
}

try{
    if(isset($activityService)){
        $activities = $activityService->getAllLimitLengthResume();
    }
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
        "allActivities.css",
    ],
    "title" => "Toutes les activitées"
]);?>

<!-- <div class="containerFullResume">
    <div>
        <div class="closeMenu"><i class="fa-solid fa-xmark"></i></div>
        <div class="resume">

        </div>
    </div>
</div> -->

<main>
    <section>
        <h2>Toutes les activités</h2>
        <?php if(isset($activityService)): ?>
            <?php if(isset($activities[0])): ?>
            <div class="container">
                <?php foreach($activities as $activity): ?>
                    <div class="cardActivity">
                        <div class="imgContainer">
                            <?php if(isset($activity->mainImg)):?>
                                <img src="<?php echo "./assets/images/".htmlspecialchars($activity->mainImg) ?>" alt="Image de l'activité">
                            <?php else:?>
                                <img src="./assets/images/unfound.png" alt="Aucune image pour cette activité.">
                            <?php endif?>
                            
                        </div>
                        <div class="descriptionContainer">
                            <h3><?php echo htmlspecialchars($activity->title) ?></h3>
                            <div class="paragraphe">
                                <?php
                                $paragraphes = explode("\n", $activity->resume);
                                for($i = 0; $i < count($paragraphes)-1; $i++){
                                    echo "<p>" . htmlspecialchars($paragraphes[$i]) . "</p>";
                                }
                                if(strlen($activity->resume) == 250){
                                    echo "<p>" . htmlspecialchars($paragraphes[$i]) . "...<span class='readFullResume' data-uuid='". $activity->uuid ."'> Lire la suite</span></p>";
                                }else{
                                    echo "<p>" . htmlspecialchars($paragraphes[$i]) . "</p>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="containerButton">
                            <a href="./voteActivity.php?uuid=<?php echo htmlspecialchars($activity->uuid) ?>">
                                <button><i class="fa-solid fa-check-to-slot"></i>Voter pour ce projet</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <?php else: ?>
                <?php Tools::errorMessage("Aucune activité enregistrée pour le moment.", "")?>
            <?php endif?>
        <?php else:?>
            <?php Tools::errorMessage("Nous n'avons pas pu appeler un service.", $errorMessage) ?>
        <?php endif?>
    </section>
</main>
<script>
    const SERVER_URL = <?php echo json_encode($_SERVER["SERVER_NAME"]); ?>
</script>
<script src="./js/guest/allActivities.js"></script>
<?php
endHTML()
?>

