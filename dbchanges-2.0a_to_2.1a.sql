ALTER TABLE `hashes` CHANGE `salt` `salt` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `hashes` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
INSERT INTO `os` (`name`) VALUES ('Unknown OS');
UPDATE `hash_algs` SET `name` = 'md5(md5($pass))' WHERE `name`='md5(md5($pass)';
CREATE TABLE `tasks_templates` (
  `id` int(11) NOT NULL,
  `project_id` mediumint(9) NOT NULL,
  `type` enum('server','server-software','domain','web-app') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tasks_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `tasks_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;