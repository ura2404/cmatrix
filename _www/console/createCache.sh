#!/bin/bash
#echo "--- --- --- --- --- --- --- ---"
#echo "Cache creator for Cmatrix by © ura@itx.ru v0.1"

# form, css, datamodel
sudo -u www-data php -r "
    require_once '../common.php';
    \Cmatrix\Kernel\Ide\Cache::get('dms')->clear();
    \Cmatrix\Kernel\Ide\Cache::get('forms')->clear();
    \Cmatrix\Kernel\Ide\Modules::cache();
"