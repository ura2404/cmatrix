#!/bin/bash
[ -z $1 ] && echo -e '------------------------------'
[ -z $1 ] && echo -e 'Утилита установки владельца и прав.'
[ -z $1 ] && echo -e  'v.0.1 by ura@urx.su'
[ -z $1 ] && echo

## --------------------------------------------------------
 # Установка прав доступа к папкам и файлам
 #---------------------------------------------------------

. common.lib

cd ../..

echo -e 'Установка владельца и прав файлов.'

echo -e $(ts) 'Owner'
chown -R www-data:www-data $folder

echo -e $(ts) 'Mode for console files.'
find $home -type f \( -name "*.php" -or -name "*.sh" \) -exec chmod 770 {} \;

echo -e $(ts) 'Mode for files.'
find $folder -type f -not -path '*/.console/*' -exec chmod 660 {} \;

echo -e $(ts) 'Mode for folders.'
find $folder -type d -exec chmod 770 {} \;

echo
cd $home
