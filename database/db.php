<?php
namespace database;
use PDO;
use PDOException;

class db{
    private PDO $pdo;

    //LES DIFFERENTS PARAMETRES DE LA CONNECTION DANS LA FONCTION __CONSTRUCT

    public function __construct($host = "localhost", $dbName = "votingSystem", $user="root", $password="admin"){
        try{
            //LA CONNECTION SE FAIT ICI DANS LE NEW PDO, si tu dois spécifier le port c'est ici que ça se passe
            $this->pdo = new PDO("mysql:host=${host}; dbname=${dbName}", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function run(string $query, $args = null){
        if(is_null($args)){
            return $this->pdo->query($query);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }

    public function beginTransaction(){
        $this->pdo->beginTransaction();
    }

    public function commitTransaction(){
        $this->pdo->commit();
    }

    public function rollbackTransaction(){
        $this->pdo->rollBack();
    }
}
?>