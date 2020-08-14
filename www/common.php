<?php
require_once dirname(__FILE__) .'/module/Cmatrix/code/utils.php';

spl_autoload_register(function($className){
    $isDm = 'cmDatamodel' === substr($className,0,11) ? true : false;
    $className = $isDm ? substr($className,11) : $className;
    
    $ClassPath = str_replace("\\",DIRECTORY_SEPARATOR,$className).'.class.php';
    //dump($ClassPath,'ClassPath');
    $Root = dirname(__FILE__).'/module';
    
    array_map(function($value) use($isDm,$Root,$ClassPath){
        if($value == '.' || $value == '..' || !is_dir($Root .'/'. $value)) return;
        $Path = $Root.'/'.$value .($isDm ? '/dm/' : '/code/'). $ClassPath;
        dump($Path);
        if(file_exists($Path)) {
            dump($Path,'require'.($isDm?1:0));
            require_once $Path;
        }
    },scandir($Root));
},true,true);

?>