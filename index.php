<?php

namespace dataParse;

use dataParse\src\FrontEnd;

/**
 * Description of index
 *
 * @author Mahabub
 */
//Autoload
require_once './vendor/autoload.php';
define('STORAGE', dirname(__FILE__) . "/storage/");

class index {

    //put your code here
    public function __construct() {
        if (self::isAjax()) {
            if (isset($_POST['method']) && !empty($_POST['method'])) {
                $backend = new src\BackEnd();
                $method = trim($_POST['method']);
                if (method_exists($backend, $method)) {
                    $backend->$method();
                } else {
                    echo "'$method' Method Not Exists !";
                }
            }
            exit;
        } else {
            FrontEnd::init();
        }
    }

    public static function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

}

new index();

