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

/*
$n = 4;
//echo $n.':'.pow(2,$n)."\n";

for($i=1; $i<pow(2,$n); $i++){
    //echo $i . '-' . sprintf("%'.0".($n+1)."s",decbin($i)) ."\n";
    echo $i . '-' . sprintf("%'.0".$n."s",decbin($i)) ."\n";
    
}
//sprintf("%'.09d\n", 123);

//dump(pow(2,$N));
//dump(decbin(pow(2,$N)));
exit;
*/

// --- --- --- --- --- --- --- ---
$_help = function($text){
    echo 'Ошибка: '. $text . PHP_EOL;
    print '
Использование: 
  1. php -f dbGenerator.php dbcreate <login> <password>, где
        - login - логин администратора БД;
        - password - пароль администратора БД.
        
  2. php -f dbGenerator.php <mode> <target> <url>, где
        -mode   - режим: script,check,create,update,fkcreate,update,init;
        -target - цель: "all", dm", "ds", "all::<provider>", "dm::<provider>", "ds::<provider>";
        -url    - url модуля, части или сущности, например "all", "Cmatrix", "Cmatrix/Core", "Cmatrix/Core/Session".
        
    - mode (режим):
        -script   - вывод SQL-скриптов
        -check    - проверка соответствия объектов DB их описаниям;
        -create   - создания в DB таблицы сущности;
        -fkcreate - создания внешних ключей в DB для таблицы сущности;
        -fkupdate - пересоздание внешних ключей в DB для таблицы сущности;
        -update   - обновление в DB таблицы сущности;
        -init     - наполнение в DB таблицы сущности начальными данными.
        
    - target (цель):
        -provider - DB provider: pdsql,mysql,sqlite3;
        -dm - модель данных;
        -ds - модель источника данных.
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
$_provider = function($target){
    $Provider = strAfter($target,'::');
    return \Cmatrix\App\Kernel::get()->Config->getValue('db/def/provider','pgsql');
};

// --- --- --- --- --- --- --- ---
/**
 * @param string $target
 * @param string $url
 */
$_script = function($target,$url) use($_help,$_provider){
    if(!$target) $_help('Не указана цель');
    if(!$url) $_help('Не указан url');
    

    $Rules = Cmatrix\Orm\Cql\Rules::get();
    
    $Rules->add('sysuser_id::int','2','>');
    $Rules->add('name','QWERT');
    /*$Rules->add([
        ['dep','qqq'],
        ['dep','www'],
    ]);
    $Rules->add(Cmatrix\Orm\Cql\Rules::get('or')
        ->add('name2','AAZ')
        ->add('name3','RTY')
    );
    $Rules->add(Cmatrix\Orm\Cql\Rules::get('and')
        ->add('name2','AAZ')
        ->add('name3','RTY')
    );
    */
    dump($Rules->Data);
    dump($Rules->Query);
    die();
    
    
    try {
        $Sql = \Cmatrix\Structure\Kernel::get($target,$url)->SqlCreate;
        dump($Sql);
        die();
        
        $Arr = \Cmatrix\Structure\Tree::get($url)->List;
        
        $Url = \Cmatrix\Kernel\Url::get($url);
        if($Url->Part3) $Sql = \Cmatrix\Structure\Kernel::get($target,$url)->SqlCreate;
        else{
            \Cmatrix\Structure\Tree::get($url)->List;
        }
    }
    catch (\Cmatrix\Kernel\Exception\Error $e) {
        $_help($e->getMessage());
    }
    catch (\Throwable $e) {
        $_help($e->getMessage());
    }
    
    echo "----------------------------------------------------------------------\n";
    echo "-- start -------------------------------------------------------------\n";
    echo "----------------------------------------------------------------------\n\n";
    echo $Sql;
    echo "\n----------------------------------------------------------------------\n";
    echo "-- end ---------------------------------------------------------------\n";
    echo "----------------------------------------------------------------------\n";
    return;
    
    //$provider = strAfter($target,'::');
    //$target = strBefore($target,'::');
    
    //if(!$provider) $provider = $_provider();
    
    //dump($provider);
    //dump($target);
    
    
    /*
    switch($target){
        case 'dm' : 
            $Model = \Cmatrix\Structure\Model::get(\Cmatrix\Kernel\Ide\Datamodel::get($url));
            //$Sql = \Cmatrix\Structure\Datamodels::get($url,$provider)->Sql;
            break;
            
        case 'ds' : 
            $Model = \Cmatrix\Structure\Model::get(\Cmatrix\Kernel\Ide\Datasource::get($url));
            //$Sql = \Cmatrix\Structure\Datasources::get($url,$provider)->Sql;
            break;
            
        default: $_help('Неверная цель');
    }
    */
    /*
    if($target === 'dm')     $Model = \Cmatrix\Structure\Model::get(\Cmatrix\Kernel\Ide\Datamodel::get($url));
    elseif($target === 'ds') $Model = \Cmatrix\Structure\Model::get(\Cmatrix\Kernel\Ide\Datasource::get($url));
    else $_help('Неверная цель');

    $Provider = \Cmatrix\Structure\Provider::get($provider);
    //$Sql = \Cmatrix\Structure\Kernel::get($Model,$Provider)->SqlCreate;
    
    $Sql = $Model->getSql($Provider);
    */
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