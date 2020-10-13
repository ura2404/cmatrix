<?php
/**
 * Загружает autoloader из каждого модуля, если есть
 */

define('CM_DS',DIRECTORY_SEPARATOR);
define('CM_ROOT',realpath(dirname(__FILE__).CM_DS.'..'));

$RootModules = CM_ROOT.CM_DS.'modules';

$Arr = scandir($RootModules);

$Arr = array_filter($Arr,function($value) use($RootModules){
    $Module = $RootModules.CM_DS.$value;
    return ($value == '.' 
        || $value == '..' 
        || substr($value,0,1) == '.'
        || (is_dir($Module) && !file_exists($Module.CM_DS.'autoloader.php'))
    ) ? false : true;
});

array_map(function($value) use($RootModules){
    require_once($RootModules.CM_DS.$value.CM_DS.'autoloader.php');
},$Arr)
?>