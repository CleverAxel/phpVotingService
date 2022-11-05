<?php

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
try {
    if (isset($_GET["uuid"])) {
        $activityService = AppProvider::getInstance()->make("activityService");
        $uuid = $_GET["uuid"];
        $activity = $activityService->getByUUID($uuid);
        if ($activity->mainImg) {
            if (file_exists("../assets/images/" . $activity->mainImg)) {
                unlink("../assets/images/" . $activity->mainImg);
            }
        }
        if ($activity->qrCode) {
            if (file_exists("../assets/images/" . $activity->qrCode)) {
                unlink("../assets/images/" . $activity->qrCode);
            }
        }
        $activityService->deleteActivityByUUID($uuid);
        Tools::redirect("allActivities.php");
    }
} catch (PDOException $e) {
    Tools::redirect("allActivities.php?error=occured");
}
?>