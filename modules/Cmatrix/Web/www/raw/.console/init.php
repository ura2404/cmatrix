#!/usr/bin/php
<?php
/**
 * Настроечный скрипт
 */

$root = realpath(dirname(__FILE__) .'/..');
$file = file_get_contents($root .'/.htaccess');
$arr = explode('AuthUserFile ', $file);
$arr2 = explode(PHP_EOL,$arr[1]);

$arr2[0] = $root .'/.htpasswd';
$arr[1] = implode(PHP_EOL,$arr2);
$file = implode('AuthUserFile ', $arr);

file_put_contents($root .'/.htaccess', $file);
?>