#!/bin/bash

#echo -e $(ts) 'Owner'
#chown -R www-data:www-data $folder

HOME=`pwd`
cd ..

echo -e 'Mode for executable files.'
find cli -type f \( -name "*.php" -or -name "*.sh" -or -name "*.bash" \) -exec chmod 770 {} \;

echo -e 'Mode for files.'
find * -type f -not -path 'cli/*' -exec chmod 660 {} \;

echo -e 'Mode for folders.'
find * -type d -exec chmod 770 {} \;

echo
cd $HOME
