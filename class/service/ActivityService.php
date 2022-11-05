<?php
namespace class\service;

require(__DIR__ . "/../validation/ValidationCreateActivity.php");

use class\tools\Tools;
use class\validation\ValidationCreateActivity;
use database\db;
use Exception;
use PDOException;

class ActivityService{
    private db $_db;
    //private string $_pathImg = "../assets/images/";
    private string $_pathImg = __DIR__ . "/../../assets/images/";

    public function __construct(db $db){
        $this->_db = $db;
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

    public function deleteActivityByUUID($uuid){
        try{
            $this->_db->run("DELETE FROM activity WHERE uuid = ?", [$uuid]);
        }catch(PDOException $e){
            throw new Exception("Delete failed");
        }
    }

    public function updateQrCode(string $generatedQrCode, string $uuid){
        try{
            $this->_db->run("UPDATE activity SET qrCode = ? WHERE uuid = ?", [$generatedQrCode, $uuid]);
        }catch(PDOException $e){
            throw new Exception("L'ajout dans la base de donnée a raté.");
        }
    }

    public function updateActivity(){
        $newTitle = $_POST["title"];
        $newResume = $_POST["resume"];
        $uuid = $_POST["uuid"];
        $needToDeleteImg = (int)$_POST["needToDeleteImg"];
        if(ValidationCreateActivity::isValid($newTitle, $newResume)){

            if($needToDeleteImg == 1){
                $activity = null;
                $newImg = null;
                try{
                    $activity = $this->getByUUID($uuid);
                }catch(Exception $e){
                    throw new Exception("Select failed");
                }

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
}
