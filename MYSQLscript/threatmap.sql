
DROP DATABASE IF EXISTS chulien_threatmap;
CREATE DATABASE IF NOT EXISTS chulien_threatmap;

USE chulien_threatmap;

CREATE TABLE END_USER_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	display_picture VARCHAR(255),
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	office_id INT(11) NOT NULL,
	department VARCHAR(255),
	rank VARCHAR(255),
	username VARCHAR(255) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL
);

CREATE TABLE CLIENT_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	display_picture VARCHAR(255),
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	office_id INT(11) NOT NULL,
	department VARCHAR(255),
	rank VARCHAR(255),
	username VARCHAR(255) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	person_to_notify VARCHAR(255) NOT NULL,
	relationship VARCHAR(255) NOT NULL,
	identification_number VARCHAR(255) NOT NULL	
);

CREATE TABLE OFFICE_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	address VARCHAR(255) NOT NULL,
	contact_person VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL
);