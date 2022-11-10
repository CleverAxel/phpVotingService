<?php

use objectClass\tools\Tools;
use provider\AppProvider;
use objectClass\service\UserService;
use objectClass\service\ActivityService;

require(__DIR__ . "/provider/AppProvider.php");

Tools::checkIfUserGotCookieToVote();
$activityService = null;
$userService = null;
try {
    $db = AppProvider::getInstance()->make("db");
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService", [$db]);
    
    /**
     * @var UserService | null
     */
    $userService = AppProvider::getInstance()->make("userService", [$db]);
    

    if(isset($activityService) && isset($userService) && isset($_GET["uuidActivity"])){
        $activity = null;
        $UUIDUser = null;
        try{
            $activity = $activityService->getByUUID($_GET["uuidActivity"]);
        }catch(Exception $e){
            Tools::redirect("allActivities.php?voteConfirm=error");
        }

        //fail si l'user a voté.    
        try{
            $UUIDUser = $userService->checkIfUserExistsInDBElseCreateIt();
        $userService->checkIfUserAlreadyVotedForActivity(/*$activity->uuid*/);
        }catch(Exception $e){
            Tools::redirect("voteActivity.php?uuid=".$_GET["uuidActivity"]."&error=".$e->getMessage());
        }

        try{
            $activityService->registerVoteForUser($activity->uuid, $UUIDUser);
            Tools::redirect("voteActivity.php?uuid=".$_GET["uuidActivity"]."&success=");
        }catch(Exception $e){
            Tools::redirect("voteActivity.php?uuid=".$_GET["uuidActivity"]."&error=".$e->getMessage());
        }
    }else{
        Tools::redirect("allActivities.php?voteConfirm=error");
    }

} catch (Exception $e) {
    Tools::redirect("allActivities.php?voteConfirm=error");
}
?>