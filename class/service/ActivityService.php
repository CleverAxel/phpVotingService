<?php
namespace class\service;
require(__DIR__ . "/../tools/Tools.php");
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
        
        //$urlQrCode = null;
        $urlMainImg = null;

        // if (!empty($_FILES["qrCode"]["name"])) {
        //     $urlQrCode = Tools::getNewFileName($_FILES["qrCode"]["name"]);
        // }
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
                    //$urlQrCode,
                    $urlMainImg
                ]);

                // if ($urlQrCode != null) {
                //     move_uploaded_file($_FILES["qrCode"]["tmp_name"], $this->_pathImg . $urlQrCode);
                // }

                if ($urlMainImg != null) {
                    move_uploaded_file($_FILES["mainImg"]["tmp_name"], $this->_pathImg . $urlMainImg);
                }

                header("Location: postActivity.php?uuid=".$uuid);
                exit;

            }catch(PDOException $e){
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
            throw new Exception("Select failed");
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

        return $query[0] ?? null;
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
}
