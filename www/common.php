<?php
require_once dirname(__FILE__) .'/module/Cmatrix/code/utils.php';

spl_autoload_register(function($className){
    $ClassPath = str_replace("\\",DIRECTORY_SEPARATOR,$className).'.class.php';
    $Root = dirname(__FILE__).'/module';
    array_map(function($value) use($Root,$ClassPath){
        if($value == '.' || $value == '..') return;
        $Path = $Root.'/'.$value.'/code/'.$ClassPath;
        if(file_exists($Path)) require_once $Path;
    },scandir($Root));
},true,true);

?>