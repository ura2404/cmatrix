<?php
require_once 'utils.php';

spl_autoload_register(function($className){
    if(class_exists($className)) return;
    
    $Arr = explode("\\",$className);
    if(count($Arr)< 3) return;
    
    $Module = array_shift($Arr);
    if($Module !== 'Cmatrix') return;
    
    $Part = array_shift($Arr);
    $ClassName = implode("\\",$Arr);
    
    $ClassPath = dirname(__FILE__) .CM_DS. $Part .CM_DS. 'code' .CM_DS. str_replace("\\",CM_DS,$ClassName) .'.class.php';
    
    if(file_exists($ClassPath)){
        require_once($ClassPath);
    }
},true,true);
?>