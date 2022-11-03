<?php

use class\service\ActivityService;
use database\db;

require(__DIR__ . "/database/db.php");
require(__DIR__ . "/Layout/layoutHTML.php");
require(__DIR__ . "/class/service/ActivityService.php");
$activityService = new ActivityService(new db());
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

<main>
    <section>
        <?php $activities = $activityService->getAll(); ?>

        <?php foreach ($activities as $activity): ?>
            <div class="activity">
                <div>
                    <section class="imgSection">
                        <?php if($activity->mainImg): ?>
                            <img src=<?php echo "./assets/images/".$activity->mainImg  ?> alt="">
                        <?php else: ?>
                            <img src="./assets/images/unfound.png" alt="not found">
                        <?php endif?>
                        
                    </section>
                    <section class="descriptionSection">
                        <h2><?php echo $activity->title ?></h2>
                        <?php
                        $paragraphes = explode("\n", $activity->resume);
                        foreach ($paragraphes as $paragraphe) {
                            echo "<p>${paragraphe}</p>";
                        }
                        ?>
                        <a href=<?php echo "./voteActivity.php?uuid=".$activity->uuid ?> class="linkToVote">
                            <button class="push-button-3d"><i class="fa-solid fa-check-to-slot"></i> VOTER POUR CE PROJET</button>
                        </a>
                    </section>

                </div>
            </div>
        <?php endforeach ?>

    </section>
</main>

<?php
endHTML()
?>