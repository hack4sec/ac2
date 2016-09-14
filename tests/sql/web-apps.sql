TRUNCATE TABLE `projects`;
INSERT INTO `projects` (`id`, `name`, `comment`, `when_add`, `updated`) VALUES (NULL, 'test project 1', '', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

TRUNCATE TABLE `os`;
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'FreeBSD');
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'Linux');

TRUNCATE TABLE `servers`;
INSERT INTO `servers` (`id`, `project_id`, `ip`, `name`, `os_id`, `nmap_result`, `comment`, `checked`, `when_add`, `updated`)
VALUES (NULL, '1', '1.1.1.1', 'server 1', '1', NULL, 'server comment 1', '0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  (NULL, '1', '2.2.2.2', 'server 2', '1', NULL, 'server comment 2', '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

TRUNCATE TABLE `domains`;
INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
  (1, 1, 'domain 1', 0, 'comment domain 1', 0, 0);
INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
  (2, 1, 'domain 2', 0, 'comment domain 2', 0, 0);

TRUNCATE TABLE `web_apps`;
INSERT INTO `web_apps` (`id`, `domain_id`, `name`, `url`, `version`, `version_unknown`, `version_old`, `vendor_site`, `need_auth`, `url_rewrite`, `ghost`, `checked`, `comment`, `updated`) VALUES
(NULL, 1, 'WebApp1', '/', '1.0a', 0, 0, '', 0, 0, 0, 0, 'testcomment1', 0),
(NULL, 1, 'WebApp2', '/a/', '1b', 1, 0, '', 0, 0, 0, 0, 'testcomment2', 0);


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

TRUNCATE TABLE `tasks`;
INSERT INTO `tasks` (`id`, `object_id`, `type`, `name`, `description`, `status`, `updated`, `when_add`) VALUES
  (NULL, 1, 'server', 'task 1', 'comment task 1', 0, 0, 0),
  (NULL, 1, 'server', 'task 2', 'comment task 2', 0, 0, 0),
  (NULL, 1, 'server-software', 'task 1', 'comment task 1', 0, 0, 0),
  (NULL, 1, 'server-software', 'task 2', 'comment task 2', 0, 0, 0),
  (NULL, 1, 'web-app', 'task 1', 'comment task 1', 0, 0, 0),
  (NULL, 1, 'web-app', 'task 2', 'comment task 2', 0, 0, 0)    ;


TRUNCATE TABLE `files`;
INSERT INTO `files` (`id`, `hash`, `name`, `object_id`, `type`, `comment`, `updated`, `when_add`) VALUES
  (NULL, '11111111111111111111111111111111', 'file1.txt', 1, 'server', 'file comment 1', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file2.txt', 1, 'server', 'file comment 2', 0, 0),
  (NULL, '11111111111111111111111111111111', 'file3.txt', 1, 'server-software', 'file comment 3', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file4.txt', 1, 'server-software', 'file comment 4', 0, 0),
  (NULL, '11111111111111111111111111111111', 'file5.txt', 1, 'web-app', 'file comment 5', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file6.txt', 1, 'web-app', 'file comment 6', 0, 0);


TRUNCATE TABLE `vulns`;
INSERT INTO `vulns` (`id`, `type`, `vuln_type_id`, `object_id`, `risk_level_id`, `name`, `description`, `exploit_link`, `updated`, `when_add`) VALUES
  (NULL, 'web-app', 3, 1, 1, 'Vuln1', 'About1', 'Link1', 0, 0),
  (NULL, 'web-app', 3, 1, 1, 'Vuln2', 'About2', 'Link2', 0, 0),
  (NULL, 'server-software', 3, 1, 1, 'Vuln1', 'About1', 'Link1', 0, 0),
  (NULL, 'server-software', 3, 1, 1, 'Vuln2', 'About2', 'Link2', 0, 0);

