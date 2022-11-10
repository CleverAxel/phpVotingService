<?php
namespace objectClass\validation;
class ValidationCreateActivity{
    public static function isValid(string $title, string $resume){
        if(trim($title) == "" || trim($resume) == ""){
            return false;
        }
        return true;
    }
}
?>