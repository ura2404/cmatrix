<?php

// --- --- --- --- --- --- --- ---
if(PHP_SAPI == 'cli') define('EOL',PHP_EOL); else define('EOL','<br/>');
if(PHP_SAPI == 'cli') define('TAB',"\t"); else define('TAB','&nbsp;');

class Table {
    private $Name;
    private $Data;
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
    function print(){
        echo '<h2>' .$this->Name. '</h2>';
        echo '<table><tbody>';
        array_map(function($key,$value){
            echo '<tr>';
            echo '<td class="e">' .$key. '</td>';
            echo '<td class="v">' .$value. '</td>';
            echo '</tr>';
        },array_keys($this->Data),array_values($this->Data));
        echo '</tbody></table>';
    }
}

// --- --- --- --- --- --- --- ---
echo '<div class="center" style="margin-bottom:150px;">';

echo '<table><tbody>';
echo '<tr class="h">';
echo '<td><h1>Cmatrix</h1></td>';
echo '</tr>';
echo '</tbody></table>';

$Database = new Table('Check Databases');
$Database->add('pgsql',shell_exec("psql -V") ? 'true' : 'false');
$Database->print();

$ApacheModules = apache_get_modules();
$Apache = new Table('Check Apache modules');
$Apache->add('mod_rewrite',in_array('mod_rewrite',$ApacheModules) ? 'true' : 'false');
$Apache->print();

$Php = new Table('Check Php modules');
//$Php->add('json',     extension_loaded('json') ? 'true' : 'false');
$Php->addBoolResult('extension_loaded',[
    'json',
    'mbstring',
    'ctype',
    'gd',
    'PDO',
    'pgsql',
    'pdo_pgsql'

]);
$Php->print();

echo '</div>';

phpinfo();
?>