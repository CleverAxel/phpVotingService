<?php
namespace class\tools;
class Tools{
    public static function getNewFileName(string $fileName){
        $parts = pathinfo($fileName);
        $fileName = uniqid($parts["filename"]) . "." . $parts["extension"];
        return $fileName;
    }
}
?>