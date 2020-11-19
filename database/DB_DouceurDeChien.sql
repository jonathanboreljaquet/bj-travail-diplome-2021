CREATE TABLE `user` (
  `id` int PRIMARY KEY,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `secondname` varchar(255) NOT NULL,
  `password_iteration_count` int NOT NULL,
  `password_salt` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `isAdministrator` boolean NOT NULL
);

CREATE TABLE `dog` (
  `id` int PRIMARY KEY,
  `chip_id` varchar(255),
  `name` varchar(255) NOT NULL,
  `client_id` int
);

CREATE TABLE `document` (
  `id` int PRIMARY KEY,
  `type` ENUM ('conditions_of_registration', 'poster', 'dog_course_summary') NOT NULL,
  `path` varchar(255) NOT NULL,
  `client_id` int
);

ALTER TABLE `dog` ADD FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);

ALTER TABLE `document` ADD FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);
