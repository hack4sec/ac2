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
(NULL, 1, 'WebApp1', '/', '', 1, 0, '', 0, 0, 0, 0, '', 0),
(NULL, 1, 'WebApp2', '/a/', '', 1, 0, '', 0, 0, 0, 0, '', 0);

TRUNCATE TABLE `risk_levels`;
INSERT INTO `risk_levels` (`id`, `name`, `css_class`, `sort`) VALUES
(1, 'First', 'rclass1', 1),
(2, 'Second', 'rclass2', 2);

TRUNCATE TABLE `vulns_types`;
INSERT INTO `vulns_types` (`id`, `name`, `type`) VALUES
(1, 'Type1', 'server-software'),
(2, 'Type2', 'server-software'),
(3, 'TypeA', 'web-app'),
(4, 'TypeB', 'web-app');


TRUNCATE TABLE `servers_software`;
INSERT INTO `servers_software` (`id`, `server_id`, `name`, `version`, `version_unknown`, `version_old`, `vendor_site`, `banner`, `port`, `ghost`, `checked`, `comment`, `updated`, `when_add`) VALUES
(0, 1, 'Apache', '1.0', 1, 0, 'http://apache.org', 'bannert', 0, 0, 0, '', 0, 0),
(0, 1, 'MySQL', '1.1', 0, 0, 'http://mysql.com', '', 0, 0, 0, '', 0, 0);


TRUNCATE TABLE `vulns`;
INSERT INTO `vulns` (`id`, `type`, `vuln_type_id`, `object_id`, `risk_level_id`, `name`, `description`, `exploit_link`, `updated`, `when_add`) VALUES
(NULL, 'web-app', 3, 1, 1, 'Vuln1', 'About1', 'Link1', 0, 0),
(NULL, 'web-app', 3, 1, 2, 'Vuln2', 'About2', 'Link2', 0, 0),
(NULL, 'server-software', 2, 1, 1, 'Vuln1', 'About1', 'Link1', 0, 0),
(NULL, 'server-software', 2, 1, 2, 'Vuln2', 'About2', 'Link2', 0, 0);

