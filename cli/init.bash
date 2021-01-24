#!/bin/bash

PWD=`pwd`
WWW_PATH=$PWD'/../www/'
RAW_PATH=$PWD'/../www/raw'

FUN_INIT="define('CM_DS',DIRECTORY_SEPARATOR);
# создать конфиги
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
\Cmatrix\App\Kernel::get()->Config;
\Cmatrix\Web\Kernel::get()->Config;
\Cmatrix\Db\Kernel::get()->Config;
"

GET_WHOME="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
echo \Cmatrix\Web\Kernel::get()->Home;
"
SET_WHOME="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
\Cmatrix\Web\Kernel::get()->Config->setValue('web/root','%%%');

return;

    define('CM_DS',DIRECTORY_SEPARATOR);
    \$Value = '%%%';
    \$File = realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'app').CM_DS.'web.config.json';
    \$Content = json_decode(file_get_contents(\$File),true);
    if(!isset(\$Content['web']) ||  !isset(\$Content['web']['root'])) return;
    \$Content['web']['root'] = \$Value;
    file_put_contents(\$File,json_encode(\$Content,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
"
GET_PATH="
    define('CM_DS',DIRECTORY_SEPARATOR);
    \$Path = realpath('\$\$\$');
    echo \$Path;
"
SET_HTACCESS="
    define('CM_DS',DIRECTORY_SEPARATOR);
    \$Key = '###';
    \$Value = '%%%';
    \$Path = realpath('\$\$\$');
    if(!file_exists(\$Path)) return;
    \$File = \$Path .CM_DS. '/.htaccess';
    \$Content = file_get_contents(\$File);
    \$Arr = explode(\$Key.' ', \$Content);
    \$Arr2 = explode(PHP_EOL,\$Arr[1]);
    \$Arr2[0] = \$Value;
    \$Arr[1] = implode(PHP_EOL,\$Arr2);
    \$Content = implode(\$Key.' ', \$Arr);
    file_put_contents(\$File, \$Content);
"
# --- --- --- --- --- --- --- --- --- ---
echo

php -r "$FUN_INIT"

WHOME=`php -r "$GET_WHOME"`

echo 'Введите путь к корню проекта в нотации web-сервера:'
[ "$WHOME" != "" ] && read -ei $WHOME WHOME || read WHOME

php -r "${SET_WHOME/'%%%'/$WHOME}"

VAR_HTACCESS=${SET_HTACCESS/'###'/'RewriteBase'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$WHOME}
php -r "${VAR_HTACCESS/\$\$\$/$WWW_PATH}"

VAR_PATH=${GET_PATH/\$\$\$/$WWW_PATH'/.htpasswd'}
VAR_PATH=`php -r "$VAR_PATH"`
VAR_HTACCESS=${SET_HTACCESS/'###'/'AuthUserFile'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$VAR_PATH}
php -r "${VAR_HTACCESS/\$\$\$/$WWW_PATH}"

VAR_PATH=${GET_PATH/\$\$\$/$RAW_PATH'/.htpasswd'}
VAR_PATH=`php -r "$VAR_PATH"`
VAR_HTACCESS=${SET_HTACCESS/'###'/'AuthUserFile'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$VAR_PATH}
php -r "${VAR_HTACCESS/\$\$\$/$RAW_PATH}"

exit


//echo "${SET_PASSWD/\$\$\$/$WHOME}"
php -r "${SET_PASSWD/\$\$\$/$WHOME}"

exit

echo '>'$WHOME'<'

ROOT=`pwd`'/../www/'

echo $ROOT




exit

/*
#!/usr/bin/env php
<?php
define('CM_DS',DIRECTORY_SEPARATOR);

$_passwd = function($path){
    $Path = $path;
    if(!file_exists($Path)) return;

    $File = $Path .CM_DS. '/.htaccess';

    $Content = file_get_contents($File);

    $Arr = explode('AuthUserFile ', $Content);
    $Arr2 = explode(PHP_EOL,$Arr[1]);

    $Arr2[0] = $Path .'/.htpasswd';
    $Arr[1] = implode(PHP_EOL,$Arr2);

    $Content = implode('AuthUserFile ', $Arr);

    file_put_contents($File, $Content);
};

$val = getopt("p:");
var_dump($val);

$_passwd(realpath(dirname(__FILE__).'/../www'));
$_passwd(realpath(dirname(__FILE__).'/../www/raw'));
?>
*/