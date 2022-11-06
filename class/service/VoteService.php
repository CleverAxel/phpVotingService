<?php
namespace class\service;

use database\db;
use Exception;
use PDOException;

class VoteService{
    private db $_db;

    public function __construct(db $db){
        $this->_db = $db;
    }

    public function process(){
        echo "<pre>";
        print_r($this->_db->run("show processlist;")->fetchAll());
        echo "</pre>";
    }
}