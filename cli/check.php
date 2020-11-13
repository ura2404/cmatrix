#!/usr/bin/env php
<?php

class Result {
    private $Name;
    private $Data = [];
    function __construct($name){
        $this->Name = $name;
    }
    function add($key,$value){
        $this->Data[$key] = $value;
    }
    function addBoolResult($fun,$key){
        if(!is_array($key)) $key = [$key];
        array_map(function($value) use($fun){
            $this->add($value,$fun($value) ? 'true' : 'false');
        },$key);
    }
    function type(){
        if(!count($this->Data)) return;
        echo PHP_EOL;
        echo $this->Name;
        echo PHP_EOL;
        
        array_map(function($key,$value){
            echo "\t".$key.' -> '.$value.PHP_EOL;
        },array_keys($this->Data),array_values($this->Data));
    }
    public function print($data){
        if(gettype($data) === 'string') echo $data .PHP_EOL;
    }
}

//Result::print(PHP_EOL);
Result::print('---------------------------------------------------------------------');
Result::print('Checking the required environment for platform constructor «Cmatrix».');

//echo PHP_EOL.'Checking the required environment for platform constructor «Cmatrix».'.PHP_EOL;

// ---
$Database = new Result('Check Databases');
$Database->add('pgsql',shell_exec("psql -V") ? 'true' : 'false');
$Database->type();

// ---
//$ApacheModules = apache_get_modules();
$ApacheModules = explode("\n",shell_exec("sudo apache2ctl -t -D DUMP_MODULES | sed '1d' | sed 's/ (static)//' | sed 's/ (shared)//' | cut -c 2-"));
$Apache = new Result('Check Apache modules');
$Apache->add('rewrite_module',in_array('rewrite_module',$ApacheModules) ? 'true' : 'false');
$Apache->type();

// ---
//$PhpModules = get_loaded_extensions();
$Php = new Result('Check Php modules');
$Php->addBoolResult('extension_loaded',[
    'json',
    'mbstring',
    'ctype',
    'gd',
    'PDO',
    'pgsql',
    'pdo_pgsql'
]);
$Php->type();
Result::print('---------------------------------------------------------------------');
?>