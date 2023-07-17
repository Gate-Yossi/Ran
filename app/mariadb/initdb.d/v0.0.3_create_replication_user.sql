
CREATE USER 'replication_user'@'%' IDENTIFIED BY 'P@ssw0rd';
GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';
