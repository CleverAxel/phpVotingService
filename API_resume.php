<?php
use provider\AppProvider;
use objectClass\service\ActivityService;

require(__DIR__ . "/provider/AppProvider.php");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
$activityService = null;
try{
    /**
     * @var ActivityService | null
     */
    $activityService = AppProvider::getInstance()->make("activityService");
    try{
        $resume = $activityService->getResumeByUUID($_GET["uuid"]);
        
        $json = json_encode([
            "error" => false,
            "resume" => $resume[0]->resume
        ]);

        $json = str_replace("\\r", "", $json);
        echo $json;
    }catch(Exception $e){
        echo json_encode([
            "error" => true
        ]);
    }
}catch(PDOException $e){
    echo json_encode([
        "error" => true
    ]);
}
?>