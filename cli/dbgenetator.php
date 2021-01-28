#!/usr/bin/env php
<?php
/**
 * Создатель таблиц
 * 
 * @author ura@urx.su
 * @version 1.0 2016-03-23
 * @version 2.0 2013-03-18
 * @version 3.0 2020-12-29
 *    -for Cmatrix v4.0
 */

require_once "../modules/common.php";

// --- --- --- --- --- --- --- ---
$_help = function($text){
    echo 'Ошибка: '. $text . PHP_EOL;
    print '
Использование: 
  1. php -f dbGenerator.php dbcreate <login> <password>, где
        - login - логин администратора БД
        - password - пароль администратора БД
        
  2. php -f dbGenerator.php <mode> <target> <url>, где
        -mode   - режим: script,check,create,update,fkcreate,update,init
        -target - цель: "all", dm", "ds", "<provider>::all", "<provider>::dm", "<provider>::ds"
        -url    - url модуля, части или сущности, например "all", "Cmatrix", "Cmatrix/Core", "Cmatrix/Core/Session" 
        
    Режим:
        -script   - вывод SQL-скриптов
        -check    - проверка соответствия объектов DB их описаниям;
        -create   - создания в DB таблицы сущности;
        -fkcreate - создания внешних ключей в DB для таблицы сущности;
        -fkupdate - пересоздание внешних ключей в DB для таблицы сущности;
        -update   - обновление в DB таблицы сущности;
        -init     - наполнение в DB таблицы сущности начальными данными.
        -provider - DB provider: pdsql,mysql,sqlite3
    Цель:
        -dm - модель данных
        -ds - модель источника данных
';
    echo PHP_EOL;
    die();
};

// --- --- --- --- --- --- --- ---
$_version = function(){
    $file = file_get_contents(__FILE__);
    $javadoc = strBefore(strAfter($file,'/**'),'*/');
    $version = strBefore(strRAfter($javadoc,'@version'),"\n");
    return $version;
};

// --- --- --- --- --- --- --- ---
$_provider = function(){
    return \Cmatrix\App\Kernel::get()->Config->getValue('db/def/provider','pgsql');
};

// --- --- --- --- --- --- --- ---
$_script = function($target,$url) use($_help,$_provider){
    if(!$target) $_help('Не указана цель');
    if(!$url) $_help('Не указан url');
    
    $provider = strAfter($target,'::');
    $target = strBefore($target,'::');
    
    if(!$provider) $provider = $_provider();
    
    //dump($provider);
    //dump($target);
    
    echo "----------------------------------------------------------------------\n";
    echo "-- start -------------------------------------------------------------\n";
    echo "----------------------------------------------------------------------\n\n";
    
    switch($target){
        case 'dm' : 
            $Sql = \Cmatrix\Structure\Datamodels::get($url,$provider)->Sql;
            break;
            
        case 'ds' : 
            $Sql = \Cmatrix\Structure\Datasources::get($url,$provider)->Sql;
            break;
            
        default: $_help('Неверная цель');
    }
    
    dump($Sql);
    
    echo "\n----------------------------------------------------------------------\n";
    echo "-- end ---------------------------------------------------------------\n";
    echo "----------------------------------------------------------------------\n";
};

echo "----------------------------------------------------------------------\n";
$version = $_version();
echo "DB creator" .($version ? ' v'.$version : null). " by © ura@urx.su\n\n";

$mode = isset($argv[1]) ? $argv[1] : null;
$arg2 = isset($argv[2]) ? $argv[2] : null;
$arg3 = isset($argv[3]) ? $argv[3] : null;

switch($mode){
    case 'script' :
        echo $_script($arg2,$arg3) . "\n";
        break;
        
    default: $_help('Не указан или неверный режим');
}
?>