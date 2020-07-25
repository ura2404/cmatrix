<?php
    require_once '../common.php';
    \Cmatrix\Kernel\Ide\Cache::get('forms')->clear();
    \Cmatrix\Kernel\Ide\Module::createCacheAll();
    echo 123;
    echo "\n";
?>