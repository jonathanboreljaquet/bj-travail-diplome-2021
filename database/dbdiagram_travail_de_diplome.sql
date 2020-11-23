CREATE TABLE `user` (
  `id` int PRIMARY KEY,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `secondname` varchar(255) NOT NULL,
  `phonenumber` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `api_token` varchar(255) NOT NULL,
  `password` varchar(255),
  `is_administrator` boolean NOT NULL
);

CREATE TABLE `dog` (
  `id` int PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `dog_breed` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `path_dog_picture` varchar(255),
  `chip_id` varchar(255),
  `client_id` int
);

CREATE TABLE `document` (
  `id` int PRIMARY KEY,
  `type` ENUM ('conditions_of_registration', 'poster', 'dog_course_summary') NOT NULL,
  `path` varchar(255) NOT NULL,
  `client_id` int
);

CREATE TABLE `courseContent` (
  `id` int PRIMARY KEY,
  `course_note_text` varchar(255),
  `path_course_note_graphical` varchar(255),
  `course_summary` varchar(255),
  `date` datetime NOT NULL,
  `client_id` int
);

ALTER TABLE `dog` ADD FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);

ALTER TABLE `document` ADD FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);

ALTER TABLE `courseContent` ADD FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);
