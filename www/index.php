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
//define('MODE',isset($_SERVER['_CM_MODE']) ? $_SERVER['CM_MODE'] : 'production');

switch(MODE){
    case 'development' :
        ini_set('display_errors',1);
        error_reporting(-1);
        break;
    case 'production' :
        ini_set('display_errors',0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.',true,503);
        echo 'Cmatrix fatal error: wrong environment or environment isn\'t defined.';
        exit(1);
}

$Page = isset($_REQUEST['cmp']) ? $_REQUEST['cmp'] : null;
echo \Cmatrix\Web\Page::get($Page)->Html;
?>