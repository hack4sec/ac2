ALTER TABLE `hashes` CHANGE `salt` `salt` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `hashes` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
INSERT INTO `os` (`name`) VALUES ('Unknown OS');
UPDATE `hash_algs` SET `name` = 'md5(md5($pass))' WHERE `name`='md5(md5($pass)';