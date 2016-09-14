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

TRUNCATE TABLE `servers_software`;
INSERT INTO `servers_software` (`id`, `server_id`, `name`, `version`, `version_unknown`, `version_old`, `vendor_site`, `banner`, `port`, `ghost`, `checked`, `comment`, `updated`, `when_add`) VALUES
(0, 1, 'Apache', '1.0', 1, 0, 'http://apache.org', 'bannert', 0, 0, 0, '', 0, 0),
(0, 1, 'MySQL', '1.1', 0, 0, 'http://mysql.com', '', 0, 0, 0, '', 0, 0);

TRUNCATE TABLE `domains`;
INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
  (1, 1, 'domain 1', 0, 'comment domain 1', 0, 123);
INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
  (2, 1, 'domain 2', 0, 'comment domain 2', 0, 123);

TRUNCATE TABLE `web_apps`;
INSERT INTO `web_apps` (`id`, `domain_id`, `name`, `url`, `version`, `version_unknown`, `version_old`, `vendor_site`, `need_auth`, `url_rewrite`, `ghost`, `checked`, `comment`, `updated`) VALUES
(NULL, 1, 'WebApp1', '/', '', 0, 0, '', 0, 0, 0, 0, '', 0),
(NULL, 1, 'WebApp2', '/a/', '', 1, 0, '', 0, 0, 0, 0, '', 0);

TRUNCATE TABLE `users_groups`;
INSERT INTO `users_groups` (`id`, `type`, `object_id`, `name`, `comment`, `when_add`, `updated`) VALUES
(NULL, 'server', 1, 'Group1', '', 0, 0),
(NULL, 'server', 1, 'Group2', '', 0, 0),
(NULL, 'server-software', 1, 'Group1', '', 0, 0),
(NULL, 'server-software', 1, 'Group2', '', 0, 0),
(NULL, 'web-app', 1, 'Group1', '', 0, 0),
(NULL, 'web-app', 1, 'Group2', '', 0, 0);

TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `group_id`, `login`, `email`, `hash_id`, `shell_id`, `home_dir`, `vip`, `updated`) VALUES
(NULL, 1, 'User1', 'email1@example.com', 1, NULL, '', 0, 0),
(NULL, 1, 'User2', 'email2@example.com', 1, NULL, '', 0, 0),
(NULL, 3, 'User1', 'email3@example.com', 1, NULL, '', 0, 0),
(NULL, 3, 'User2', 'email4@example.com', 1, NULL, '', 0, 0),
(NULL, 5, 'User1', 'email5@example.com', 1, NULL, '', 0, 0),
(NULL, 5, 'User2', 'email6@example.com', 1, NULL, '', 0, 0);

TRUNCATE TABLE `hash_algs`;
INSERT INTO `hash_algs` (`id`, `name`) VALUES
(1, 'MD5'),
(2, 'SHA1');

TRUNCATE TABLE `hashes`;
INSERT INTO `hashes` (`id`, `hash`, `salt`, `password`, `user_id`, `alg_id`) VALUES
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 1, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 2, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 3, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 4, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 5, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 6, 1);

TRUNCATE TABLE `shells`;
INSERT INTO `shells` (`id`, `name`) VALUES
(1, '/bin/false'),
(2, '/bin/bash');

