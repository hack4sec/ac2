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

TRUNCATE TABLE `tasks`;
INSERT INTO `tasks` (`id`, `object_id`, `type`, `name`, `description`, `status`, `updated`, `when_add`) VALUES
  (NULL, 1, 'server', 'task 1', 'comment task 1', 2, 0, 0),
  (NULL, 1, 'server', 'task 2', 'comment task 2', 2, 0, 0),
  (NULL, 1, 'server-software', 'task 3', 'comment task 1', 2, 0, 0),
  (NULL, 1, 'server-software', 'task 4', 'comment task 2', 2, 0, 0),
  (NULL, 1, 'web-app', 'task 5', 'comment task 1', 2, 0, 0),
  (NULL, 1, 'web-app', 'task 6', 'comment task 2', 2, 0, 0),
  (NULL, 1, 'domain', 'task 7', 'comment task 1', 2, 0, 0),
  (NULL, 1, 'domain', 'task 8', 'comment task 2', 2, 0, 0),
  (NULL, 1, 'project', 'task 9', 'comment task 1', 2, 0, 0),
  (NULL, 1, 'project', 'task 10', 'comment task 2', 2, 0, 0)
  ;