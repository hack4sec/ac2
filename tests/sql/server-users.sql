TRUNCATE TABLE `domains`;
TRUNCATE TABLE `servers_software`;
TRUNCATE TABLE `servers`;

TRUNCATE TABLE `projects`;
INSERT INTO `projects` (`id`, `name`, `comment`, `when_add`, `updated`) VALUES (NULL, 'test project 1', '', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

TRUNCATE TABLE `os`;
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'FreeBSD');
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'Linux');


INSERT INTO `servers` (`id`, `project_id`, `ip`, `name`, `os_id`, `nmap_result`, `comment`, `checked`, `when_add`, `updated`)
VALUES (NULL, '1', '1.1.1.1', 'server 1', '1', NULL, 'server comment 1', '0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       (NULL, '1', '2.2.2.2', 'server 2', '1', NULL, 'server comment 2', '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

TRUNCATE TABLE `servers_users_groups`;
INSERT INTO `servers_users_groups` (`id`, `server_id`, `name`, `comment`, `when_add`, `updated`) VALUES
(NULL, 1, 'Group1', '', 0, 0),
(NULL, 1, 'Group2', '', 0, 0);

TRUNCATE TABLE `servers_users`;
INSERT INTO `servers_users` (`id`, `group_id`, `login`, `email`, `hash_id`, `shell_id`, `home_dir`, `vip`, `updated`) VALUES
(NULL, 1, 'User1', 'wegweg', 0, NULL, '', 0, 0),
(NULL, 1, 'User2', 'wegweg', 0, NULL, '', 0, 0);

TRUNCATE TABLE `shells`;
INSERT INTO `shells` (`id`, `name`) VALUES
(1, '/bin/false'),
(2, '/bin/bash');

