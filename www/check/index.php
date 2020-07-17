<?php

// ---

if(PHP_SAPI == 'cli') define('EOL',PHP_EOL); else define('EOL','<br/>');
if(PHP_SAPI == 'cli') define('TAB',"\t"); else define('TAB','&nbsp;');


// --- --- --- --- --- --- --- ---
echo "Check Databases:". EOL;
$_databases = [
    'pgsql' => function(){
        $Version = shell_exec("psql -V");
        return !!$Version;
    },
];

array_map(function($key,$value){
    echo TAB. $key .TAB.TAB.TAB. ($value() ? 'OK' : 'Fail') . EOL;
},array_keys($_databases),array_values($_databases));


// --- --- --- --- --- --- --- ---
echo  EOL."Check Apache modules:". EOL;
$ModulesApache = [
    'mod_rewrite'
];
$LoadedModules = apache_get_modules();
array_map(function($value) use($LoadedModules){
    echo TAB. $value .TAB.TAB.TAB. (in_array($value,$LoadedModules) ? 'OK' : 'Fail') . EOL;
},$ModulesApache);

// --- --- --- --- --- --- --- ---
echo  EOL."Check PHP modules:". EOL;
$ModulesPHP = [
    'json',
    'mbstring',
    'ctype',
    'PDO',
    'pgsql',
    'pdo_pgsql',
    'gd',
];

array_map(function($value){
    echo TAB. $value .TAB.TAB.TAB. (extension_loaded($value) ? 'OK' : 'Fail') . EOL;
},$ModulesPHP);

echo '';

?>