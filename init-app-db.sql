-- Delete previous DB and create a new one.
-- #####################################################################
DROP DATABASE IF EXISTS footballdb;

CREATE DATABASE IF NOT EXISTS footballdb;

USE footballdb;
-- #####################################################################

-- Delete previous user and create a new one.
-- #####################################################################
DROP USER IF EXISTS footballusr;

CREATE USER IF NOT EXISTS 'footballusr'@'localhost' IDENTIFIED BY 'footballpass';

GRANT ALL ON footballdb.* TO footballusr@localhost;
-- #####################################################################
