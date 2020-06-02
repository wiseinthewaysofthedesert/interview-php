CREATE DATABASE IF NOT EXISTS CurrencyConverterDB;
USE CurrencyConverterDB;
CREATE USER 'test'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON CurrencyConverterDB.* TO 'test'@'localhost';


