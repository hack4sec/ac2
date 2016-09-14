TRUNCATE TABLE `projects`;
INSERT INTO `projects` (`id`, `name`, `comment`, `when_add`, `updated`) VALUES (NULL, 'test project 1', '', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

TRUNCATE TABLE `os`;
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'FreeBSD');
INSERT INTO `os` (`id`, `name`) VALUES (NULL, 'Linux');

TRUNCATE TABLE `servers`;
INSERT INTO `servers` (`id`, `project_id`, `ip`, `name`, `os_id`, `nmap_result`, `comment`, `checked`, `when_add`, `updated`)
VALUES (NULL, '1', '1.1.1.1', 'server 1', '1', NULL, 'server comment 1', '0', 0, 0),
       (NULL, '1', '2.2.2.2', 'server 2', '1', NULL, 'server comment 2', '1', 0, 0);

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

TRUNCATE TABLE `files`;
INSERT INTO `files` (`id`, `hash`, `name`, `object_id`, `type`, `comment`, `updated`, `when_add`) VALUES
  (NULL, '11111111111111111111111111111111', 'file1.txt', 1, 'server', 'file comment 1', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file2.txt', 1, 'server', 'file comment 2', 0, 0),
    (NULL, '11111111111111111111111111111111', 'file3.txt', 1, 'server-software', 'file comment 3', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file4.txt', 1, 'server-software', 'file comment 4', 0, 0),
    (NULL, '11111111111111111111111111111111', 'file5.txt', 1, 'web-app', 'file comment 5', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file6.txt', 1, 'web-app', 'file comment 6', 0, 0),
    (NULL, '11111111111111111111111111111111', 'file7.txt', 1, 'domain', 'file comment 7', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file8.txt', 1, 'domain', 'file comment 8', 0, 0),
    (NULL, '11111111111111111111111111111111', 'file9.txt', 1, 'project', 'file comment 9', 0, 0),
  (NULL, '22222222222222222222222222222222', 'file10.txt', 1, 'project', 'file comment 10', 0, 0);
