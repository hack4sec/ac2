TRUNCATE TABLE `web_apps`;

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
  (1, 1, 'domain 1', 0, 'comment domain 1', 0, 123);
INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
  (2, 1, 'domain 2', 0, 'comment domain 2', 0, 123);

TRUNCATE TABLE `web_apps`;
INSERT INTO `web_apps` (`id`, `domain_id`, `name`, `url`, `version`, `version_unknown`, `version_old`, `vendor_site`, `need_auth`, `url_rewrite`, `ghost`, `checked`, `comment`, `updated`) VALUES
(NULL, 1, 'WebApp1', '/', '', 0, 0, '', 0, 0, 0, 0, 'testcomment1', 0),
(NULL, 1, 'WebApp2', '/a/', '', 1, 0, '', 0, 0, 0, 0, 'testcomment2', 0);
