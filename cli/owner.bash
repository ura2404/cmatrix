#!/bin/bash

HOME=`pwd`
FOLDER=`pwd | grep -o [^/]* | sed -e '$!{h;d;}' -e x`

cd ../..

chmod 770 $FOLDER

cd $FOLDER

echo -e 'Mode for executable files.'
find cli -type f \( -name "*.php" -or -name "*.sh" -or -name "*.bash" \) -exec chmod 770 {} \;

echo -e 'Mode for files.'
find * -type f -not -path 'cli/*' -exec chmod 660 {} \;

echo -e 'Mode for folders.'
find * -type d -exec chmod 770 {} \;

echo
cd $HOME
