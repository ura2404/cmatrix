<?php
require_once(realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common.php'));

//dump(CM_MODE);
switch(CM_MODE){
    case 'development' :
        ini_set('display_errors',1);
        error_reporting(-1);
        break;
    case 'production' :
        ini_set('display_errors',0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        //E_CORE_WARNING
        break;
    default :
        header('HTTP/1.1 503 Service Unavailable.',true,503);
        echo 'Cmatrix fatal error: wrong environment or environment isn\'t defined.';
        exit(1);
}

$Page = isset($_REQUEST['cmp']) ? rtrim($_REQUEST['cmp'],'/') : null;
echo \Cmatrix\Web\Page::get($Page)->Html;

/*
try{
    $Page = isset($_REQUEST['cmp']) ? rtrim($_REQUEST['cmp'],'/') : null;
    echo \Cmatrix\Web\Page::get($Page)->Html;
}
catch(\Cmatrix\Kernel\Exception $e){
    //echo '\Cmatrix\Kernel\Exception\Error';
    //echo $e->getMessage();
    echo 'Что-то пошло не так';
}
catch(\Throwable2 $e){
    echo $e->getMessage();
    //dump($e->getTrace());
    
    $Page = \Cmatrix\Web\Exception::get($e);
    $Page->Exception = $e;
    echo $Page->Html;
}
catch(\Error2 $e){
    echo 'Error->'.$e->getMessage();
}
catch(\TypeError $e){
    echo 'TypeError->'.$e->getMessage();
}
catch(\ParseError $e){
    echo 'ParseError->'.$e->getMessage();
}
*/

/*
catch(\Exception $e){
    echo 'Exception->';
    echo $e->getMessage();
}
*/
?>