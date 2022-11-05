<?php
namespace provider;

use class\service\ActivityService;
use database\db;
require(__DIR__ . "/../class/tools/Tools.php");
require(__DIR__ . "/../class/service/ActivityService.php");
require(__DIR__ . "/../database/db.php");

class AppProvider{
    /**
     * @var AppProvider
     */
    private static $_appProvider;

    private $_bindings = [];

    public function __construct(){
        $this->_bindings["activityService"] = function(){
            return new ActivityService(new db());
        };
    }

    public function bind(string $name, callable $call){
        $this->_bindings[$name] = $call;
    }

    public function make(string $name){
        return $this->_bindings[$name]();
    }

    public static function getInstance(){
        if(!isset(self::$_appProvider)){
            self::$_appProvider = new AppProvider();
        }

        return self::$_appProvider;
    }
}

AppProvider::getInstance();
?>