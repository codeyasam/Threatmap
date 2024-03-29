
DROP DATABASE IF EXISTS chulien_threatmap;
CREATE DATABASE IF NOT EXISTS chulien_threatmap;

USE chulien_threatmap;

CREATE TABLE OFFICE_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	municipality VARCHAR(255),
	province VARCHAR(255),
	country VARCHAR(255),
	contact_person VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE END_USER_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	display_picture VARCHAR(255),
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	office_id INT(11) NOT NULL,
	CONSTRAINT user_office_id FOREIGN KEY(office_id)
	REFERENCES OFFICE_TB(id),
	department VARCHAR(255),
	rank VARCHAR(255),
	username VARCHAR(255) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	user_type VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE CLIENT_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255) NOT NULL,
    display_picture VARCHAR(255),
	address VARCHAR(255) NOT NULL,
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL,
	contact_no VARCHAR(255) NOT NULL,
	office_id INT(11),
	CONSTRAINT client_office_id FOREIGN KEY(office_id)
	REFERENCES OFFICE_TB(id),
	department VARCHAR(255),
	rank VARCHAR(255),
	username VARCHAR(255) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	person_to_notify VARCHAR(255) NOT NULL,
	relationship VARCHAR(255) NOT NULL,
	identification_number VARCHAR(255) NOT NULL	
);

CREATE TABLE THREAT_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	description VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	municipality VARCHAR(255),
	province VARCHAR(255),
	country VARCHAR(255),
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL
);

CREATE TABLE NOTIFICATION_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	client_id INT(11) NOT NULL,
	CONSTRAINT notifs_client_id FOREIGN KEY(client_id)
	REFERENCES CLIENT_TB(id),
	address VARCHAR(255) NOT NULL,
	municipality VARCHAR(255),
	lat FLOAT(10,6) NOT NULL,
	lng FLOAT(10,6) NOT NULL,
	submit_dtime DATETIME NOT NULL,
	status INT(11) NOT NULL
);

CREATE TABLE SESSION_USER_TB (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) NOT NULL,
	CONSTRAINT session_user_id FOREIGN KEY(user_id)
	REFERENCES END_USER_TB(id),
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL
);

#DEFAULT VALUES

INSERT INTO OFFICE_TB (name, address, municipality, province, country, contact_person, contact_no, lat, lng) 
VALUES ('Malolos Crossing Police Station', 'Manila N Rd, Malolos, Bulacan, Philippines',
'Malolos', 'Bulacan', 'Philippines', 'John Doe', '0922222222', 14.852578, 120.816063);

INSERT INTO OFFICE_TB (name, address, municipality, province, country, contact_person, contact_no, lat, lng) 
VALUES ('Bulacan Provincial Police Office', 'Unnamed Road, Paombong, Bulacan, Philippines',
'Paombong', 'Bulacan', 'Philippines', 'Lorem Doe', '0922222222', 14.852578, 120.816063);

INSERT INTO OFFICE_TB (name, address, municipality, province, country, contact_person, contact_no, lat, lng) 
VALUES ('Sumapang Matanda a Barangay Hall', 'Sumapa Ligas Rd, Malolos, Bulacan, Philippines',
'Malolos', 'Bulacan', 'Philippines', 'Doe Lorem', '0922222222', 14.857890, 120.822988);

INSERT INTO END_USER_TB (first_name, last_name, middle_name, display_picture,
address, contact_no, office_id, department, rank, username, password, user_type) VALUES (
'Maryjo Estrella', 'Bautista', 'Delfin', 'DISPLAY_PICTURES/default_avatar.png', 'Sumapa Ligas Rd, Malolos, Bulacan, Philippines',
'09069081822', 1, 'Architecture Security', 'Senior Software Architect', 'codeyasam', '7510d498f23f5815d3376ea7bad64e29', 'SUPERADMIN');

INSERT INTO END_USER_TB (first_name, last_name, middle_name, display_picture,
address, contact_no, office_id, department, rank, username, password, user_type) VALUES (
'Maryjo Estrella', 'Bautista', 'Delfin', 'DISPLAY_PICTURES/default_avatar.png', 'Sumapa Ligas Rd, Malolos, Bulacan, Philippines',
'09069081822', 1, 'Architecture Security', 'Architect', 'jojo', '7510d498f23f5815d3376ea7bad64e29', '');

INSERT INTO END_USER_TB (first_name, last_name, middle_name, display_picture,
address, contact_no, office_id, department, rank, username, password, user_type) VALUES (
'Emmanuel', 'Yasa', 'Pescasio', 'DISPLAY_PICTURES/default_avatar.png', 'Sumapa Ligas Rd, Malolos, Bulacan, Philippines',
'09069081822', 1, 'Software Architecture', 'Software Architect', 'yasa', '7510d498f23f5815d3376ea7bad64e29', '');

#populate clients table
INSERT INTO CLIENT_TB (first_name, last_name, middle_name, display_picture,
address, lat, lng, contact_no, username, password, person_to_notify,
relationship, identification_number) VALUES ('code', 'yasa', 'm', 'DISPLAY_PICTURES/default_avatar.png', '538 Daisy St, Malolos, 3000 Bulacan, Philippines', 
'14.854902','120.835943','09194348867', 'codeyasam', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'Amor Yasa', 'Mother', '1234567');

INSERT INTO CLIENT_TB (first_name, last_name, middle_name, display_picture,
address, lat, lng, contact_no, username, password, person_to_notify,
relationship, identification_number) VALUES ('david', 'gasnerr', 'yeah', 'DISPLAY_PICTURES/default_avatar.png', '909 Sumapa Ligas Rd, Malolos, 3000 Bulacan, Philippines', 
'14.861677','120.844219','09194348867', 'david', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'Gasnerr Rin', 'Mother', '7654321');

INSERT INTO NOTIFICATION_TB (client_id, address, municipality, lat, lng, submit_dtime, status)
VALUES (1, 'Unnamed Road, Santa Cruz, Zambales, Philippines', 'Santa Cruz', 15.747144, 120.093018, '2016-08-28 18:16:45', 0);

INSERT INTO NOTIFICATION_TB (client_id, address, municipality, lat, lng, submit_dtime, status)
VALUES (1, 'Unnamed Road, General Nakar, Quezon, Philippines', 'General Nakar', 14.623275, 121.477295, '2016-08-28 18:16:45', 0);

INSERT INTO NOTIFICATION_TB (client_id, address, municipality, lat, lng, submit_dtime, status)
VALUES (2, 'Unnamed Road, Diadi, Nueva Vizcaya, Philippines', 'Diadi', 16.696535, 121.345459, '2016-08-28 18:16:45', 0);

INSERT INTO NOTIFICATION_TB (client_id, address, municipality, lat, lng, submit_dtime, status)
VALUES (2, 'Pan-Philippine Hwy, Carranglan, Nueva Ecija, Philippines', 'Minuli', 16.085228, 120.927979, '2016-08-28 18:16:45', 0);
