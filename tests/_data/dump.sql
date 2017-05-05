/* Replace this file with actual dump of your database */
/*CREATE TABLE IF NOT EXISTS album
(
	id INT not null AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	date TIMESTAMP NOT NULL,
	description VARCHAR(500) NULL,
	dir VARCHAR(512) NOT NULL COMMENT 'Место расположения альбома',
	order_param INT DEFAULT '-1' NOT NULL
);

INSERT INTO album (
  name,
  date,
  description,
  dir,
  order_param)

VALUES (
  'TEST',
  '2017-05-01 13:34:37',
  'This album contains images of dogs',
  'D:/OpenServer/domains/localhost/projects/php.gallery/app/web/upload-images/1_dogs',
  -1
);

SET @last_insert_id = LAST_INSERT_ID();

DELETE FROM gallery.album WHERE id=@last_insert_id;*/