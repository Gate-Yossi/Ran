#!/bin/bash

until mariadb-admin ping -hmariadb -uroot -p${MARIADB_ROOT_PASSWORD} --silent; do
    sleep 1
done

mariadb -uroot -p${MARIADB_ROOT_PASSWORD} -e" \
    SET GLOBAL gtid_slave_pos = '';
    CHANGE MASTER TO \
        master_host='mariadb', \
        master_port=3306, \
        master_user='replication_user', \
        master_password='P@ssw0rd', \
        master_use_gtid=current_pos;
    START SLAVE;
    SHOW SLAVE STATUS\G
"
