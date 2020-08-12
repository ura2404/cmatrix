<?php
/**
 * Cmatrix
 *
 * ECM/CMS/DMS платформа
 *
 * @author ura@itx.ru
 */

require_once'common.php';

define('MODE',isset($_SERVER['CM_MODE']) ? $_SERVER['CM_MODE'] : null);
//define('MODE',isset($_SERVER['CM_MODE']) ? $_SERVER['CM_MODE'] : 'production');

switch(MODE){
    case 'development' :
        ini_set('display_errors',1);
        error_reporting(-1);
        break;
    case 'production' :
        ini_set('display_errors',0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        //E_CORE_WARNING
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.',true,503);
        echo 'Cmatrix fatal error: wrong environment or environment isn\'t defined.';
        exit(1);
}

try{
    $Page = isset($_REQUEST['cmp']) ? rtrim($_REQUEST['cmp'],'/') : null;
    echo \Cmatrix\Web\Page::get($Page)->Html;
}
catch(\Throwable $e){
    $Page = \Cmatrix\Web\Exception::get($e);
    $Page->Exception = $e;
    echo $Page->Html;
}
catch(\Error $e){
    echo 'Error->';
}
catch(\TypeError $e){
    echo 'TypeError->';
}
catch(\ParseError $e){
    echo 'ParseError->';
}

/*
catch(\Exception $e){
    echo 'Exception->';
    echo $e->getMessage();
}
*/
?>