#!/usr/bin/php
<?php
/**
 * Создатель кеша шаблонов форм
 * 
 * @author ura@urx.su
 * @version 3.0 2020-07-23
 *     переделан под новый движок
 * @version 2.0 2018-08-17
 *     переделан под новый движок
 * @version 1.0 2016-03-17
 */

require_once "../common.php";

echo "\n---------------------------------------------\n";
echo "Form cache creator for Cmatrix by © ura@urx.su\n";

\Cmatrix\Kernel\Ide\Cache::get('forms')->clear();
\Cmatrix\Kernel\Ide\Module::createCacheAll();
?>