<?php
namespace class\service;

use database\db;
use Exception;

class UserService{
    private db $_db;

    public function __construct(db $db){
        $this->_db = $db;
    }

    public function process(){
        echo "<pre>";
        print_r($this->_db->run("show processlist;")->fetchAll());
        echo "</pre>";
    }

    public function checkIfUserExistsInDBElseCreateIt(){
        $uuidUserDB = null;
        $uuidCookie = $_COOKIE["uuidVote"];
        try{
            $uuidUserDB = $this->_db->run("SELECT uuid FROM user WHERE uuid = ? LIMIT 1", [$uuidCookie])->fetchColumn();
        }catch(Exception $e){
            throw new Exception("Failed select user db");
        }

        if($uuidUserDB != null){
            return $uuidUserDB;
        }else{
            try{
                $this->_db->run("INSERT INTO user(uuid) VALUES(?)", [$uuidCookie]);
                return $uuidCookie;
            }catch(Exception $e){
                throw new Exception("Failed insert new user in db");
            }
        }
    }

    public function checkIfUserAlreadyVotedForActivity($uuidActivity){
        $uuidUserDB = null;
        $uuidCookie = $_COOKIE["uuidVote"];

        try{
            $uuidUserDB = $this->_db->run("SELECT uuid FROM user WHERE uuid = ? LIMIT 1", [$uuidCookie])->fetchColumn();
        }catch(Exception $e){
            throw new Exception("Failed select user db");
        }

        if($uuidUserDB != null){
            $userActivity = null;
            try{
                $userActivity = $this->_db->run("SELECT * FROM user_activity WHERE uuid_user = ? AND uuid_activity = ? LIMIT 1;", [$uuidUserDB, $uuidActivity])->fetchAll();
            }catch(Exception $e){
                throw new Exception("Failed select user activity");
            }

            if($userActivity != null){
                throw new Exception("Vous avez déjà voté pour cette activité.");
            }
        }else{
            throw new Exception("L'utilisateur n'existe pas");
        }
    }
}