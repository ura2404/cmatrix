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
GET_CONF="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
echo \Cmatrix\@@@\Kernel::get()->Config->getValue('###');
"
SET_CONF="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
\Cmatrix\@@@\Kernel::get()->Config->setValue('###','%%%');
"



GET_CODE="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
echo \Cmatrix\App\Kernel::get()->Config->getValue('app/code');
"
SET_CODE="define('CM_DS',DIRECTORY_SEPARATOR);
\$Common=realpath(dirname(__FILE__).CM_DS.'..'.CM_DS.'modules'.CM_DS.'common.php');
require_once(\$Common);
\Cmatrix\App\Kernel::get()->Config->setValue('app/code','%%%');
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
"

GET_PATH="
    define('CM_DS',DIRECTORY_SEPARATOR);
    \$Path = realpath('@@@');
    echo \$Path;
"
SET_HTACCESS="
    define('CM_DS',DIRECTORY_SEPARATOR);
    \$Key = '###';
    \$Value = '%%%';
    \$Path = realpath('@@@');
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

# 0. --- --- --- ---
php -r "$FUN_INIT"

# 1. --- --- --- ---
# получить код проекта
#CODE=`php -r "$GET_CODE"`
CONF=$GET_CONF
CONF=${CONF/'###'/'app/code'}
CONF=${CONF/'@@@'/'App'}
CODE=`php -r "$CONF"`

# получить имя проекта
#CODE=`php -r "$GET_CODE"`
CONF=$GET_CONF
CONF=${CONF/'###'/'app/name'}
CONF=${CONF/'@@@'/'App'}
NAME=`php -r "$CONF"`

# получить whome
#WHOME=`php -r "$GET_WHOME"`
CONF=$GET_CONF
CONF=${CONF/'###'/'web/root'}
CONF=${CONF/'@@@'/'Web'}
WHOME=`php -r "$CONF"`

# 2. --- --- --- ---
echo 'Введите уникальный код проекта:'
[ "$CODE" != "" ] && read -ei $CODE CODE || read CODE

echo 'Введите краткое имя проекта:'
[ "$NAME" != "" ] && read -ei $NAME NAME || read NAME

echo 'Введите путь к корню проекта в нотации web-сервера:'
[ "$WHOME" != "" ] && read -ei $WHOME WHOME || read WHOME

# 3. --- --- --- ---
# установить код проекта
CONF=$SET_CONF
CONF=${CONF/'###'/'app/code'}
CONF=${CONF/'@@@'/'App'}
php -r "${CONF/'%%%'/$CODE}"

# установить имя проекта
CONF=$SET_CONF
CONF=${CONF/'###'/'app/name'}
CONF=${CONF/'@@@'/'App'}
php -r "${CONF/'%%%'/$NAME}"

# установить whome
CONF=$SET_CONF
CONF=${CONF/'###'/'web/root'}
CONF=${CONF/'@@@'/'Web'}
php -r "${CONF/'%%%'/$WHOME}"

#
VAR_HTACCESS=${SET_HTACCESS/'###'/'RewriteBase'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$WHOME}
php -r "${VAR_HTACCESS/'@@@'/$WWW_PATH}"

#
VAR_PATH=${GET_PATH/'@@@'/$WWW_PATH'/.htpasswd'}
VAR_PATH=`php -r "$VAR_PATH"`
VAR_HTACCESS=${SET_HTACCESS/'###'/'AuthUserFile'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$VAR_PATH}
php -r "${VAR_HTACCESS/'@@@'/$WWW_PATH}"

#
VAR_PATH=${GET_PATH/'@@@'/$RAW_PATH'/.htpasswd'}
VAR_PATH=`php -r "$VAR_PATH"`
VAR_HTACCESS=${SET_HTACCESS/'###'/'AuthUserFile'}
VAR_HTACCESS=${VAR_HTACCESS/'%%%'/$VAR_PATH}
php -r "${VAR_HTACCESS/'@@@'/$RAW_PATH}"

#IGN=`cat ../.gitignore | grep '.htaccess'`
#[ "$IGN" == "" ] && echo '.htaccess' >> ../.gitignore
