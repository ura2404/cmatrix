#!/bin/bash
#echo "--- --- --- --- --- --- --- ---"
#echo "Form cache creator for Cmatrix by © ura@itx.ru"

sudo -u www-data php -r "
    require_once '../common.php';
    \Cmatrix\Kernel\Ide\Cache::get('forms')->clear();
    \Cmatrix\Kernel\Ide\Module::createCacheAll();
"
