
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
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255) NOT NULL,
    display_picture VARCHAR(255),
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
	name VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	contact_person VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL
);

#DEFAULT VALUES

INSERT INTO OFFICE_TB (name, address, contact_person, contact_no, lat, lng) 
VALUES ('Malolos Crossing Police Station', 'Manila N Rd, Malolos, Bulacan, Philippines',
 'John Doe', '0922222222', 14.852578, 120.816063);

INSERT INTO OFFICE_TB (name, address, contact_person, contact_no, lat, lng) 
VALUES ('Bulacan Provincial Police Office', 'Brgy Kapitolyo Road, Malolos, Bulacan, Philippines',
 'Lorem Doe', '0922222222', 14.852578, 120.816063);

INSERT INTO OFFICE_TB (name, address, contact_person, contact_no, lat, lng) 
VALUES ('Sumapang Matanda a Barangay Hall', 'Sumapa Ligas Rd, Malolos, Bulacan, Philippines',
 'Doe Lorem', '0922222222', 14.857890, 120.822988);

INSERT INTO END_USER_TB (first_name, last_name, middle_name, display_picture,
address, contact_no, office_id, department, rank, username, password) VALUES (
'Maryjo Estrella', 'Bautista', 'Delfin', 'display_picture/default_avatar.png', '220 Sumapang Bata Malolos City', '09069081822',
1, 'Security Architecture', 'Architect', 'jojo', 'jojo');
