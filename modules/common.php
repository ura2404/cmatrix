<?php
/**
 * 1. Определение констант
 *   - CM_MODE - режим:
 *     - development - разработка
 *     - production - штатаная эксплуатация
 * 
 *   - CM_DS - directory separator, разделитель директорый в файловых путях (\ - windows, / - linux)
 * 
 *   - CM_ROOT - корневыя директория в нотации web-сервера
 * 
 * 2. Загрузка autoloader`ов из каждого модуля, если есть
 */

define('CM_MODE',isset($_SERVER['CM_MODE']) ? $_SERVER['CM_MODE'] : null);
//define('CM_MODE','production');

!defined('CM_DS') ? define('CM_DS',DIRECTORY_SEPARATOR) : null;
!defined('CM_ROOT') ? define('CM_ROOT',realpath(dirname(__FILE__).CM_DS.'..')) : null;

$Root = CM_ROOT.CM_DS.'modules';
$Arr = scandir($Root);

$Arr = array_filter($Arr,function($value) use($Root){
    $Module = $Root.CM_DS.$value;
    return ($value == '.' 
        || $value == '..' 
        || !is_dir($Module)
        || (is_dir($Module) && !file_exists($Module.CM_DS.'autoloader.php'))
    ) ? false : true;
});
        //|| substr($value,0,1) == '.'

array_map(function($value) use($Root){
    require_once($Root.CM_DS.$value.CM_DS.'autoloader.php');
},$Arr)

?>