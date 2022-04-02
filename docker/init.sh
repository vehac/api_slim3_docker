#!/bin/bash

echo "------------------ Permissions folder ---------------------"
bash -c 'chmod -R 777 /var/www/html/logs'

echo "------------------ Init DB --------------------------------"
if [ $(mysql -u root -p7*DBslim369 -h70.21.21.12 db_slim -sse "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='db_slim' AND table_name='client'") -eq 1 ];
then
        echo "Database exists"
else
        echo "Database not exist, init dump ...."
        mysql -u root -p7*DBslim369 -h70.21.21.12 db_slim < /var/www/html/docker/my_db.sql
fi

echo "------------------ Starting apache server ------------------"
exec "apache2-foreground"