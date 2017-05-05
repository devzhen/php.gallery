/* Replace this file with actual dump of your database */
CREATE TABLE IF NOT EXISTS album
(
  id          INT              NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255)     NOT NULL,
  date        TIMESTAMP        NOT NULL,
  description VARCHAR(500)     NULL,
  dir         VARCHAR(512)     NOT NULL
  COMMENT 'Место расположения альбома',
  order_param INT DEFAULT '-1' NOT NULL
);

CREATE TABLE IF NOT EXISTS image
(
  id          INT              NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255)     NOT NULL,
  album_id    INT,
  src         VARCHAR(255)     NOT NULL,
  dir         VARCHAR(255)     NOT NULL,
  order_param INT DEFAULT '-1' NOT NULL,
  FOREIGN KEY (album_id) REFERENCES gallery.album (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

INSERT INTO gallery.album (
  id, name, date, description, dir, order_param)
VALUES (
  1,
  'Dogs',
  '2017-05-01 13:34:37',
  'This album contains images of dogs',
  'D:/OpenServer/domains/localhost/projects/php.gallery/app/web/upload-images/1_dogs',
  -1
);

INSERT INTO gallery.album (
  id, name, date, description, dir, order_param)
VALUES (
  2,
  'Cats',
  '2017-05-02 13:34:54',
  'This album contains images of cats',
  'D:/OpenServer/domains/localhost/projects/php.gallery/app/web/upload-images/2_cats',
  -1
);

INSERT INTO gallery.album (
  id, name, date, description, dir, order_param)
VALUES (
  3,
  'Motorcycles',
  '2017-05-03 13:35:15',
  'This album contains images of motorcycles',
  'D:/OpenServer/domains/localhost/projects/php.gallery/app/web/upload-images/3_motorcycles',
  -1
);

INSERT INTO album (
  id, name, date, description, dir, order_param)
VALUES (
  4,
  'Nature',
  '2017-05-04 13:35:28',
  'This album contains images of nature',
  'D:/OpenServer/domains/localhost/projects/php.gallery/app/web/upload-images/4_nature',
  -1
);

INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (1, '1.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e786594_1.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e786594_1.jpg', 1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (2, '2.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e786d64_2.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e786d64_2.jpg', 2);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (3, '3.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e786d64_3.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e786d64_3.jpg', 3);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (4, '4.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e78714c_4.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e78714c_4.jpg', 0);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (5, '5.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e787534_5.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e787534_5.jpg', 4);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (6, 'images (1).jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e78791c_images (1).jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e78791c_images(1).jpg',
   5);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (7, 'images (2).jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e787d04_images (2).jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e787d04_images(2).jpg',
   6);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (8, 'images (3).jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e787d04_images (3).jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e787d04_images(3).jpg',
   7);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (9, 'images (4).jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e7880ec_images (4).jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e7880ec_images(4).jpg',
   8);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (10, 'images (5).jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e7880ec_images (5).jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e7880ec_images(5).jpg',
   9);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (11, 'images.jpg', 4, 'http://php.gallery/app/web/upload-images/4_nature/590b05e7884d4_images.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/4_nature/590b05e7884d4_images.jpg',
   10);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (12, '1.jpg', 3, 'http://php.gallery/app/web/upload-images/3_motorcycles/590b2a42c9d0e_1.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/3_motorcycles/590b2a42c9d0e_1.jpg',
   -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (13, '2.jpg', 3, 'http://php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca0f6_2.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca0f6_2.jpg',
   -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (14, '3.jpg', 3, 'http://php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca0f6_3.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca0f6_3.jpg',
   -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (15, '4.jpg', 3, 'http://php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca4de_4.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/3_motorcycles/590b2a42ca4de_4.jpg',
   -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (16, '5.jpg', 3, 'http://php.gallery/app/web/upload-images/3_motorcycles/590b2a42cacae_5.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/3_motorcycles/590b2a42cacae_5.jpg',
   -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (17, '1.jpg', 2, 'http://php.gallery/app/web/upload-images/2_cats/590b2a7b9d718_1.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/2_cats/590b2a7b9d718_1.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (18, '2.jpg', 2, 'http://php.gallery/app/web/upload-images/2_cats/590b2a7b9db00_2.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/2_cats/590b2a7b9db00_2.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (19, '3.jpg', 2, 'http://php.gallery/app/web/upload-images/2_cats/590b2a7b9db00_3.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/2_cats/590b2a7b9db00_3.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (20, '4.jpg', 2, 'http://php.gallery/app/web/upload-images/2_cats/590b2a7b9dee8_4.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/2_cats/590b2a7b9dee8_4.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (21, '1.jpg', 1, 'http://php.gallery/app/web/upload-images/1_dogs/590b2a8ca2135_1.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/1_dogs/590b2a8ca2135_1.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (22, '2.jpg', 1, 'http://php.gallery/app/web/upload-images/1_dogs/590b2a8ca251d_2.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/1_dogs/590b2a8ca251d_2.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (23, '3.jpg', 1, 'http://php.gallery/app/web/upload-images/1_dogs/590b2a8ca251d_3.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/1_dogs/590b2a8ca251d_3.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (24, '4.jpg', 1, 'http://php.gallery/app/web/upload-images/1_dogs/590b2a8ca34bd_4.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/1_dogs/590b2a8ca34bd_4.jpg', -1);


INSERT INTO gallery.image (id, name, album_id, src, dir, order_param)
VALUES
  (25, '5.jpg', 1, 'http://php.gallery/app/web/upload-images/1_dogs/590b2a8ca38a5_5.jpg',
   'D:\\OpenServer\\domains\\localhost\\projects\\php.gallery/app/web/upload-images/1_dogs/590b2a8ca38a5_5.jpg', -1);