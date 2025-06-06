CREATE DATABASE IF NOT EXISTS uniorthocrin;
CREATE USER IF NOT EXISTS 'uniorthocrin_user'@'localhost' IDENTIFIED BY 'sua_senha_segura';
GRANT ALL PRIVILEGES ON uniorthocrin.* TO 'uniorthocrin_user'@'localhost';
FLUSH PRIVILEGES; 