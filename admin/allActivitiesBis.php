<?php
$errorMessage = "";

use class\tools\Tools;
use provider\AppProvider;
use class\service\ActivityService;
require(__DIR__ . "/../Layout/layoutHTML.php");
require(__DIR__ . "/../provider/AppProvider.php");
Tools::guardAdmin("login.php");

/**
 * @var ActivityService
 */
$activityService = "";
try{
    $activityService = AppProvider::getInstance()->make("activityService");
}catch(PDOException $e){
    $errorMessage = "CODE : " . $e->getCode() . " MESSAGE : " . $e->getMessage();
}

declareHTML([
    "path" => ".././",
    "stylesheet" => [
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "admin/allActivities.css"
    ],
    "title" => "Toutes les activités"
]); ?>
<div id="containerAlert"></div>
<main>
    <section>
        <h2>Toute les activités</h2>
        <div class="containActivities">
            <?php $activities = $activityService->getAll();?>
            <?php if(isset($activities[0])): ?>

                <?php foreach ($activities as $activity):?>
                    <div class="activity">
                        <h3><span>Titre:</span> <?php echo $activity->title ?></h3>
                        <a href=<?php echo "./deleteActivity.php?uuid=".$activity->uuid ?> data-uuid=<?php echo $activity->uuid ?> class="delete" id="delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <a href=<?php echo "./detailsActivity.php?uuid=".$activity->uuid ?> class="details">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                        <a href=<?php echo "./editActivity.php?uuid=".$activity->uuid ?> class="edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                <?php endforeach ?>
                
            <?php else: ?>
                <h3>Aucune activité enregistrée</h3>
            <?php endif?>
        </div>
    </section>
</main>

<script>
    const DELETE_BUTTON = document.getElementById("delete");
    const CONTAINER_ALERT = document.getElementById("containerAlert");
    let isPopUpOn = false;
    document.addEventListener("keydown", (e) =>{
        if(e.key == "Enter" && isPopUpOn){
            location.href = "deleteActivity.php?uuid=" + DELETE_BUTTON.dataset.uuid;
        }else if(e.key == "Escape"){
            isPopUpOn = false;
            CONTAINER_ALERT.removeChild(CONTAINER_ALERT.querySelector(".ALERT_DELETE"));
        }
    })
    if(DELETE_BUTTON){
        DELETE_BUTTON.addEventListener("click", (e) => {
            isPopUpOn = true;
            e.preventDefault();
            let div = document.createElement("div");
            div.classList.add("ALERT_DELETE");

            div.innerHTML = `
                <div>
                    <h2>Vous allez supprimer définitivement cette activité, continuer ?</h2>
                    <div class="containerButton">
                        <button>OUI</button>
                        <button>NON</button>
                    </div>
                </div>
            `

            CONTAINER_ALERT.appendChild(div);
            div.focus();
            CONTAINER_ALERT.querySelector("button:nth-child(1)").addEventListener("click", () =>{
                location.href = "deleteActivity.php?uuid=" + DELETE_BUTTON.dataset.uuid;
            });
            CONTAINER_ALERT.querySelector("button:nth-child(2)").addEventListener("click", () =>{
                isPopUpOn = false;
                CONTAINER_ALERT.removeChild(CONTAINER_ALERT.querySelector(".ALERT_DELETE"));
            })
            //location.href = "deleteActivity.php?uuid=" + DELETE_BUTTON.dataset.uuid;
        })
    }
</script>

<?php
endHTML();
?>