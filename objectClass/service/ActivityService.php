<?php
namespace objectClass\service;

require(__DIR__ . "/../validation/ValidationCreateActivity.php");

use objectClass\tools\Tools;
use objectClass\validation\ValidationCreateActivity;
use database\db;
use Exception;
use PDOException;

class ActivityService{
    private $_db;
    //private string $_pathImg = "../assets/images/";
    private $_pathImg = __DIR__ . "/../../assets/images/";

    public function __construct($db){
        /**
         * @var db
         */
        $this->_db = $db;
    }

    public function process(){
        echo "<pre>";
        print_r($this->_db->run("show processlist;")->fetchAll());
        echo "</pre>";
    }

    /**
     * Essaye d'insérer la nouvelle activité dans la base de donnée.
     * Il lancera une exception si le titre est déjà existant la BDD
     * Il lancera une exception si le formulaire
     * /!\HEADER LOCATION N A PAS LA GLOBALE __DIR__
     */
    public function insertActivityInDb(){
        $title = $_POST["title"];
        $resume = $_POST["resume"];
        $urlMainImg = null;
        
        if (!empty($_FILES["mainImg"]["name"])) {
            
            $urlMainImg = Tools::getNewFileName($_FILES["mainImg"]["name"]);
        }

        if(ValidationCreateActivity::isValid($title, $resume)){
            $uuid = null;
            try{
                $uuid = $this->_db->run("SELECT uuid FROM activity WHERE title = ? LIMIT 1", [$title])->fetchColumn();
                if($uuid != null){
                    throw new Exception("Titre déjà existant la base de donnée.");
                }
            }catch(PDOException $e){
                throw new Exception("Le test pour voir si le titre existe déjà a raté.");
            }
            $uuid = uniqid();
            try{
                $this->_db->run("INSERT INTO activity(uuid, title, resume, /*qrCode,*/ mainImg) VALUES (?, ?, ?, /*?,*/ ?)", [
                    $uuid,
                    $title,
                    $resume,
                    $urlMainImg
                ]);

                if ($urlMainImg != null) {
                    move_uploaded_file($_FILES["mainImg"]["tmp_name"], $this->_pathImg . $urlMainImg);
                }

                Tools::redirect("detailsActivity.php?uuid=".$uuid);

            }catch(Exception $e){
                throw new Exception("L'ajout dans la base de donnée a raté.");
            }
            
        }else{
            throw new Exception("Formulaire invalide, le champs du titre ainsi que du résumé est obligatoire.");
        }
    }

    public function getAll(){
        $query = null;
        try{
          $query = $this->_db->run("SELECT * FROM activity")->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Un problème est survenu au moment de récupérer toutes les activités.", (int)$e->getCode());
        }
        return $query;
    }

    public function getAllActivitiesAdminPanel(){
        $query = null;
        try{
          $query = $this->_db->run("SELECT uuid, title, qrCode, mainImg FROM activity")->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Un problème est survenu au moment de récupérer toutes les activités.", (int)$e->getCode());
        }
        return $query;
    }

    public function getResumeByUUID($uuid){
        $result = null;
        try{
            $result = $this->_db->run("SELECT resume FROM activity WHERE uuid = ?", [$uuid])->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Un problème est survenu au moment de récupérer le résumé.", (int)$e->getCode());
        }
        return $result;
    }

    public function getAllLimitLengthResume(){
        $results = null;
        try{
            $results = $this->_db->run("SELECT uuid, title, SUBSTRING(resume, 1, 250) AS resume, mainImg FROM activity;")->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Un problème est survenu au moment de récupérer toutes les activités.", (int)$e->getCode());
        }

        return $results;
    }

    public function getByUUID($uuid){
        $query = null;
        try{
            $query = $this->_db->run("SELECT * FROM activity WHERE uuid = ? LIMIT 1", [$uuid])->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Select failed");
        }

        if(!isset($query[0])){
            throw new Exception("Aucune activité trouvée avec cette UUID");
        }
        return $query[0];
    }

    public function getByTitle($title){
        $query = null;
        $wildcard = "%" . $title . "%";
        try{
            $query = $this->_db->run("SELECT * FROM activity WHERE title LIKE ? LIMIT 1", [$wildcard])->fetchAll();
        }catch(PDOException $e){
            throw new Exception("Select failed");
        }

        if(!isset($query[0])){
            throw new Exception("Aucune activité trouvée avec ce titre");
        }
        return $query[0];
    }

    public function createQrCode(string $base64){
        $filename = uniqid("QRCODE_") . ".svg";
        $stream = fopen("../assets/images/".$filename, "x");
        fwrite($stream, base64_decode($base64));
        fclose($stream);
        return $filename;
    }

    public function updateQrCode(string $generatedQrCode, string $uuid){
        try{
            $this->_db->run("UPDATE activity SET qrCode = ? WHERE uuid = ?", [$generatedQrCode, $uuid]);
        }catch(PDOException $e){
            throw new Exception("L'ajout dans la base de donnée a raté.");
        }
    }

    public function deleteActivityByUUID($uuid){
        try{
            $this->_db->run("DELETE FROM activity WHERE uuid = ?", [$uuid]);
        }catch(PDOException $e){
            throw new Exception("Delete failed");
        }
    }


    public function updateActivity(){
        $newTitle = $_POST["title"];
        $newResume = $_POST["resume"];
        $uuid = $_POST["uuid"];
        $needToDeleteImg = (int)$_POST["needToDeleteImg"];

        if(ValidationCreateActivity::isValid($newTitle, $newResume)){
            $activity = null;

            
            $activity = $this->getByUUID($uuid);

            try{
                $uuidDB = $this->_db->run("SELECT uuid FROM activity WHERE title = ? LIMIT 1", [$newTitle])->fetchColumn();
                if($uuidDB != null && $uuidDB != $uuid){
                    throw new Exception("Votre titre, ". $newTitle .", est déjà pris par une autre activité");
                }
                // if(isset($uuidDB) && $uuidDB != $uuid){
                //     throw new Exception("Votre titre, ". $newTitle .", est déjà pris par une autre activité");
                // }
            }catch(PDOException $e){
                throw new Exception("Le test pour voir si le titre existe déjà a raté.");
            }

            if(!isset($activity->qrCode)){
                $addQrCode = (int)$_POST["addQrCode"];
                if($addQrCode == 1){
                    $base64 = $_POST["base64"];
                    $filenameBase64 = $this->createQrCode($base64);
                    $this->updateQrCode($filenameBase64, $uuid);
                }
            }

            if($needToDeleteImg == 1){
                $newImg = null;
                //s'il y a une image on la supprime.
                if(isset($activity->mainImg)){
                    $oldImg = $activity->mainImg;
                    if(file_exists($this->_pathImg . $oldImg)){
                        unlink($this->_pathImg . $oldImg);
                    }
                }

                //s'il y a une nouvelle image à upload, on l'upload et l'ajoute dans la DB SINON
                //on met à null l'URL de l'image
                if (!empty($_FILES["mainImg"]["name"])) {
                    $newImg = Tools::getNewFileName($_FILES["mainImg"]["name"]);
                    try{
                        $this->_db->run("UPDATE activity SET title = ?, resume = ?, mainImg = ? WHERE uuid = ?", [$newTitle, $newResume, $newImg, $uuid]);
                        move_uploaded_file($_FILES["mainImg"]["tmp_name"], $this->_pathImg . $newImg);
                    }catch(Exception $e){
                        throw new Exception("L'ajout dans la base de donnée a raté.");
                    }
                }else{
                    try{
                        $this->_db->run("UPDATE activity SET title = ?, resume = ?, mainImg = ? WHERE uuid = ?", [$newTitle, $newResume, null, $uuid]);
                    }catch(Exception $e){
                        throw new Exception("L'ajout dans la base de donnée a raté.");
                    }
                }
            }else{
                try{
                    $this->_db->run("UPDATE activity SET title = ?, resume = ? WHERE uuid = ?", [$newTitle, $newResume, $uuid]);
                }catch(Exception $e){
                    throw new Exception("L'ajout dans la base de donnée a raté.");
                }
            }
        }else{
            throw new Exception("Le titre et le résumé ne peuvent pas être vides.");
        }

    }

    public function registerVoteForUser($uuidActivity, $uuidUser){
        try{
            $this->_db->beginTransaction();
            $this->_db->run("INSERT INTO user_activity(uuid_activity, uuid_user) VALUES(?, ?)", [$uuidActivity, $uuidUser]);
            //$this->_db->run("UPDATE activity SET vote = vote + 1 WHERE uuid = ?", [$uuidActivity]);
            $this->_db->commitTransaction();
        }catch(Exception $e){
            $this->_db->rollbackTransaction();
            throw new Exception("Failed to register vote and activity to user");
        }
    }

    public function getCountActivity(){
        try{
            $count = $this->_db->run("SELECT COUNT(uuid) FROM activity;")->fetchColumn();
            return $count;
        }catch(Exception $e){
            throw new Exception("Impossible de compter les activités.");
        }
    }

    public function getVoteByActivity(){
        try{
            $query = $this->_db->run(
            "SELECT a.title, COUNT(ua.uuid_activity) as countVoteByUser
            FROM activity as a
            INNER JOIN user_activity as ua on ua.uuid_activity = a.uuid
            GROUP BY a.title;")->fetchAll();
            return $query;
        }catch(Exception $e){
            throw new Exception("Impossible de compter les votes par activité");
        }
    }

    public function deleteAll(){
        $results = null;
        try{
            $results = $this->_db->run(
                "SELECT mainImg, qrCode
                FROM activity
                WHERE mainImg IS NOT NULL OR qrCode IS NOT NULL;")->fetchAll();
        }catch(Exception $e){
            throw new Exception("FAIL SELECT IMG AND QR CODE");
        }

        foreach ($results as $result) {
            if($result->mainImg && file_exists($this->_pathImg . $result->mainImg)){
                unlink($this->_pathImg . $result->mainImg);
            }
            if($result->qrCode && file_exists($this->_pathImg . $result->qrCode)){
                unlink($this->_pathImg . $result->qrCode);
            }
        }

        try{
            $this->_db->beginTransaction();
            $this->_db->run(
            "SET SQL_SAFE_UPDATES = 0;
            DELETE FROM activity;
            SET SQL_SAFE_UPDATES = 1;");
            $this->_db->commitTransaction();
        }catch(Exception $e){
            $this->_db->rollbackTransaction();
            throw new Exception("FAIL DELETE EVERYTHING ACTIVITY");
        }
    }
}
