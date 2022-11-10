<?php
namespace objectClass\service;

use objectClass\tools\Tools;
use database\db;
use Exception;

class UserService{
    private $_db;

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

    public function checkIfUserExistsInDBElseCreateIt(){
        $uuidUserDB = null;
        $uuidCookie = null;

        if(isset($_COOKIE["uuidVote"])){
            $uuidCookie = $_COOKIE["uuidVote"];
        }else{
            $uuidCookie = uniqid();
            Tools::setCookieForAWeek("uuidVote", $uuidCookie);
        }

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
                throw new Exception("Failed insert new user in db", 100);
            }
        }
    }

    public function checkIfUserAlreadyVotedForActivity(/*$uuidActivity*/){
        $uuidUserDB = null;
        $uuidCookie = null;
        if(isset($_COOKIE["uuidVote"])){
            $uuidCookie = $_COOKIE["uuidVote"];
        }

        try{
            $uuidUserDB = $this->_db->run("SELECT uuid FROM user WHERE uuid = ? LIMIT 1", [$uuidCookie])->fetchColumn();
        }catch(Exception $e){
            throw new Exception("Failed select user db");
        }

        if($uuidUserDB != null){
            $userActivity = null;
            try{
                $userActivity = $this->_db->run("SELECT * FROM user_activity WHERE uuid_user = ? /*AND uuid_activity = ?*/ LIMIT 1;", [$uuidUserDB/*, $uuidActivity*/])->fetchAll();
            }catch(Exception $e){
                throw new Exception("Failed select user activity");
            }

            if($userActivity != null){
                //throw new Exception("Vous avez déjà voté pour cette activité.");
                throw new Exception("Vous avez déjà voté pour une activité.");
            }
        }else{
            if($uuidCookie != null){
                throw new Exception("L'utilisateur n'existe pas");
            }
        }
    }

    public function getCountUser(){
        try{
            $count = $this->_db->run("SELECT COUNT(uuid) FROM user;")->fetchColumn();
            return $count;
        }catch(Exception $e){
            throw new Exception("Impossible de compter les utilisateurs.");
        }
    }

    public function getNumberOfVotesByUser(){
        try{
            $average = $this->_db->run(
                "WITH countVote as(
                    SELECT count(uuid_activity) as countVoteByUser
                    from user_activity
                    group by uuid_user
                )
                SELECT AVG(countVoteByUser) as averageVoteByUser
                FROM countVote;")->fetchColumn();
            return $average;
        }catch(Exception $e){
            throw new Exception("Impossible de compter le nbr de votes par utilisateur");
        }
    }

    public function deleteAll(){
        try{
            $this->_db->beginTransaction();
            $this->_db->run(
                "SET SQL_SAFE_UPDATES = 0;
                DELETE FROM user;
                SET SQL_SAFE_UPDATES = 1;"
            );
            $this->_db->commitTransaction();
        }catch(Exception $e){
            $this->_db->rollbackTransaction();
            throw new Exception("FAILED DELETED EVERYTHING USER");
        }
    }
}