<?php

use class\tools\Tools;
use provider\AppProvider;

require(__DIR__ . "/provider/AppProvider.php");

Tools::checkIfUserGotCookieToVote();
/**
 * @var ActivityService
 */

try {

    //Tools::redirect("allActivities.php?vote=success");
} catch (PDOException $e) {
    Tools::redirect("allActivities.php?vote=error");
}
?>