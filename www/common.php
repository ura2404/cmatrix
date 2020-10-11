<?php
require_once dirname(__FILE__) .'/../module/Cmatrix/utils.php';

spl_autoload_register(function($className){
    if(class_exists($className)) return;
    
    $Pos = strpos($className,"\\");
    $Module = substr($className,0,$Pos);
    $ClassName = substr($className,$Pos+1);
    
    if(!$Module) return;
    
    $ClassPath = str_replace("\\",DIRECTORY_SEPARATOR,$ClassName);
    //$ClassPath = 'Datamodel' === substr($ClassPath,0,9) ? 'dm'.DIRECTORY_SEPARATOR.substr($ClassPath,10) : 'code'.DIRECTORY_SEPARATOR.$ClassPath;
    //$ClassPath = dirname(__FILE__) .DIRECTORY_SEPARATOR.'module'.DIRECTORY_SEPARATOR.$Module .DIRECTORY_SEPARATOR. $ClassPath.'.class.php';
    $ClassPath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..')
        .DIRECTORY_SEPARATOR.'module'
        .DIRECTORY_SEPARATOR.$Module
        .DIRECTORY_SEPARATOR.$ClassPath.'.class.php';

    //dump($ClassPath);
    
    if(file_exists($ClassPath)) require_once($ClassPath);
},true,true);

?>