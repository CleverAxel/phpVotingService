<?php
namespace provider;

use database\db;
use class\service\ActivityService;
use class\service\UserService;

require(__DIR__ . "/../class/tools/Tools.php");
require(__DIR__ . "/../class/service/UserService.php");
require(__DIR__ . "/../class/service/ActivityService.php");
require(__DIR__ . "/../database/db.php");

class AppProvider{
    /**
     * @var AppProvider
     */
    private static $_appProvider;

    private $_bindings = [];

    public function __construct(){
        $this->_bindings["db"] = function(?array $args){
            return new db();
        };

        /*Pour les services, je peux décider si j'utilise une connexion par défaut
        ou une connexion que je passe par un paramètre.
        */
        $this->_bindings["activityService"] = function(?array $args){
            /**
             * @var db | null;
             */
            $DBAlternative = null;
            if(isset($args)){
                $DBAlternative = $args[0];
            }
            if(isset($DBAlternative)){
                return new ActivityService($DBAlternative);
            }else{
                return new ActivityService(new db());
            }
        };

        $this->_bindings["userService"] = function(?array $args){
            /**
             * @var db | null;
             */
            $DBAlternative = null;
            if(isset($args)){
                $DBAlternative = $args[0];
            }
            if(isset($DBAlternative)){
                return new UserService($DBAlternative);
            }else{
                return new UserService(new db());
            }
        };
    }

    public function bind(string $name, callable $call){
        $this->_bindings[$name] = $call;
    }

    public function make(string $name, array $args = null){
        return $this->_bindings[$name]($args);
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