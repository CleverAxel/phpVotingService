<?php
namespace class\tools;
class Tools{
    public static function getNewFileName(string $fileName){
        $parts = pathinfo($fileName);
        $fileName = uniqid($parts["filename"]) . "." . $parts["extension"];
        return $fileName;
    }

    public static function checkIfCookieExist(string $name){
        return isset($_COOKIE[$name]);
    }

    public static function setCookieForAWeek(string $name, string $value){
        setcookie($name, $value, strtotime("+7 days"));
    }

    public static function guardAdmin(string $redirect){
        if(!self::checkIfCookieExist("iamanadmin")){
            self::redirect($redirect);
        }
    }

    public static function redirect(string $redirect){
        header("Location: ".$redirect);
        exit;
    }

    public static function errorMessage(string $mainMessage, string $errorMessage){
        echo 
        '
        <div class="containErrorMessage">
        <h3>'. $mainMessage .'</h3>
        <h4>MESSAGE D\'ERREUR : ' . $errorMessage . '</h4>
        </div>
        ';
    }
}
?>