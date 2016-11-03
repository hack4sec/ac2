SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `server_id` int(8) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `updated` int(10) UNSIGNED DEFAULT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`) VALUES
(1, 2, 'example.com', 0, 'main web domain', 1473417920, 1473417920),
(2, 2, 'beta.example.com', 0, 'Developers domain', 1473417932, 1473417932),
(3, 1, 'gw.example.com', 0, 'Admin router domain', 1473417994, 1473417994);

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `name` varchar(150) NOT NULL,
  `object_id` int(10) UNSIGNED NOT NULL,
  `type` enum('project','server','server-software','domain','web-app') NOT NULL,
  `comment` text NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `files` (`id`, `hash`, `name`, `object_id`, `type`, `comment`, `updated`, `when_add`) VALUES
(1, '92b5209b5ee090168853e87368602e36', 'etc_passwd', 1, 'project', '/etc/passwd of main server', 1473671142, 1473671142),
(2, '600beb24513fb6932edadfb8deff8da7', 'backup.sql', 1, 'project', 'Found baskup of DB', 1473671168, 1473671168);

CREATE TABLE `hashes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(10) UNSIGNED NOT NULL,
  `alg_id` smallint(5) UNSIGNED NOT NULL,
  `cracked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `hashes` (`id`, `hash`, `salt`, `password`, `user_id`, `alg_id`, `cracked`) VALUES
(1, '', '', 'Admin', 1, 1, 1);

CREATE TABLE `hash_algs` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `hash_algs` (`id`, `name`) VALUES
(1, 'MD4'),
(2, 'MD5'),
(3, 'Half MD5'),
(4, 'SHA1'),
(5, 'SHA-384'),
(6, 'SHA-256'),
(7, 'SHA-512'),
(8, 'SHA-3(Keccak)'),
(9, 'SipHash'),
(10, 'RipeMD160'),
(11, 'Whirlpool'),
(12, 'GOST R 34.11-94'),
(13, 'GOST R 34.11-2012 (Streebog) 256-bit'),
(14, 'GOST R 34.11-2012 (Streebog) 512-bit'),
(15, 'md5($pass.$salt)'),
(16, 'md5($salt.$pass)'),
(17, 'md5(unicode($pass).$salt)'),
(18, 'md5($salt.unicode($pass))'),
(19, 'md5($salt.$pass.$salt)'),
(20, 'md5($salt.md5($pass))'),
(21, 'md5(md5($pass))'),
(22, 'md5(strtoupper(md5($pass)))'),
(23, 'md5(sha1($pass))'),
(24, 'sha1($pass.$salt)'),
(25, 'sha1($salt.$pass)'),
(26, 'sha1(unicode($pass).$salt)'),
(27, 'sha1($salt.unicode($pass))'),
(28, 'sha1(sha1($pass)'),
(29, 'sha1(md5($pass))'),
(30, 'sha1($salt.$pass.$salt)'),
(31, 'sha256($pass.$salt)'),
(32, 'sha256($salt.$pass)'),
(33, 'sha256(unicode($pass).$salt)'),
(34, 'sha256($salt.unicode($pass))'),
(35, 'sha512($pass.$salt)'),
(36, 'sha512($salt.$pass)'),
(37, 'sha512(unicode($pass).$salt)'),
(38, 'sha512($salt.unicode($pass))'),
(39, 'HMAC-MD5 (key'),
(40, 'HMAC-MD5 (key'),
(41, 'HMAC-SHA1 (key'),
(42, 'HMAC-SHA1 (key'),
(43, 'HMAC-SHA256 (key'),
(44, 'HMAC-SHA256 (key'),
(45, 'HMAC-SHA512 (key'),
(46, 'HMAC-SHA512 (key'),
(47, 'phpass'),
(48, 'scrypt'),
(49, 'PBKDF2-HMAC-MD5'),
(50, 'PBKDF2-HMAC-SHA1'),
(51, 'PBKDF2-HMAC-SHA256'),
(52, 'PBKDF2-HMAC-SHA512'),
(53, 'Skype'),
(54, 'WPA/WPA2'),
(55, 'iSCSI CHAP authentication, MD5(Chap)'),
(56, 'IKE-PSK MD5'),
(57, 'IKE-PSK SHA1'),
(58, 'NetNTLMv1'),
(59, 'NetNTLMv1 + ESS'),
(60, 'NetNTLMv2'),
(61, 'IPMI2 RAKP HMAC-SHA1'),
(62, 'Kerberos 5 AS-REQ Pre-Auth etype 23'),
(63, 'DNSSEC (NSEC3)'),
(64, 'Cram MD5'),
(65, 'PostgreSQL Challenge-Response Authentication '),
(66, 'MySQL Challenge-Response Authentication (SHA1'),
(67, 'SIP digest authentication (MD5)'),
(68, 'SMF (Simple Machines Forum)'),
(69, 'phpBB3'),
(70, 'vBulletin < v3.8.5'),
(71, 'vBulletin > v3.8.5'),
(72, 'MyBB'),
(73, 'IPB (Invison Power Board)'),
(74, 'WBB3 (Woltlab Burning Board)'),
(75, 'Joomla < 2.5.18'),
(76, 'Joomla > 2.5.18'),
(77, 'Wordpress'),
(78, 'PHPS'),
(79, 'Drupal7'),
(80, 'osCommerce'),
(81, 'xt:Commerce'),
(82, 'PrestaShop'),
(83, 'Django (SHA-1)'),
(84, 'Django (PBKDF2-SHA256)'),
(85, 'Mediawiki B type'),
(86, 'Redmine'),
(87, 'PostgreSQL'),
(88, 'MSSQL(2000)'),
(89, 'MSSQL(2005)'),
(90, 'MSSQL(2012)'),
(91, 'MSSQL(2014)'),
(92, 'MySQL323'),
(93, 'MySQL4.1/MySQL5'),
(94, 'Oracle H: Type (Oracle 7+)'),
(95, 'Oracle S: Type (Oracle 11+)'),
(96, 'Oracle T: Type (Oracle 12+)'),
(97, 'Sybase ASE'),
(98, 'EPiServer 6.x < v4'),
(99, 'EPiServer 6.x > v4'),
(100, 'Apache $apr1$'),
(101, 'ColdFusion 10+'),
(102, 'hMailServer'),
(103, 'nsldap, SHA-1(Base64), Netscape LDAP SHA'),
(104, 'nsldaps, SSHA-1(Base64), Netscape LDAP SSHA'),
(105, 'SSHA-512(Base64), LDAP {SSHA512}'),
(106, 'CRC32'),
(107, 'LM'),
(108, 'NTLM'),
(109, 'Domain Cached Credentials (DCC), MS Cache'),
(110, 'Domain Cached Credentials 2 (DCC2), MS Cache '),
(111, 'MS-AzureSync PBKDF2-HMAC-SHA256'),
(112, 'descrypt, DES(Unix), Traditional DES'),
(113, 'BSDiCrypt, Extended DES'),
(114, 'md5crypt $1$, MD5(Unix)'),
(115, 'bcrypt $2*$, Blowfish(Unix)'),
(116, 'sha256crypt $5$, SHA256(Unix)'),
(117, 'sha512crypt $6$, SHA512(Unix)'),
(118, 'OSX v10.4'),
(119, 'OSX v10.5'),
(120, 'OSX v10.6'),
(121, 'OSX v10.7'),
(122, 'OSX v10.8'),
(123, 'OSX v10.9'),
(124, 'OSX v10.10'),
(125, 'AIX {smd5}'),
(126, 'AIX {ssha1}'),
(127, 'AIX {ssha256}'),
(128, 'AIX {ssha512}'),
(129, 'Cisco-PIX'),
(130, 'Cisco-ASA'),
(131, 'Cisco-IOS $1$'),
(132, 'Cisco-IOS $4$'),
(133, 'Cisco-IOS $8$'),
(134, 'Cisco-IOS $9$'),
(135, 'Juniper Netscreen/SSG (ScreenOS)'),
(136, 'Juniper IVE'),
(137, 'Android PIN'),
(138, 'Citrix Netscaler'),
(139, 'RACF'),
(140, 'GRUB 2'),
(141, 'Radmin2'),
(142, 'SAP CODVN B (BCODE)'),
(143, 'SAP CODVN F/G (PASSCODE)'),
(144, 'SAP CODVN H (PWDSALTEDHASH) iSSHA-1'),
(145, 'Lotus Notes/Domino 5'),
(146, 'Lotus Notes/Domino 6'),
(147, 'Lotus Notes/Domino 8'),
(148, 'PeopleSoft'),
(149, '7-Zip'),
(150, 'RAR3-hp'),
(151, 'Android FDE < v4.3'),
(152, 'eCryptfs'),
(153, 'MS Office <'),
(154, 'MS Office <'),
(155, 'MS Office <'),
(156, 'MS Office <'),
(157, 'MS Office <'),
(158, 'MS Office <'),
(159, 'MS Office 2007'),
(160, 'MS Office 2010'),
(161, 'MS Office 2013'),
(162, 'PDF 1.1 - 1.3 (Acrobat 2 - 4)'),
(163, 'PDF 1.1 - 1.3 (Acrobat 2 - 4) + collider-mode'),
(164, 'PDF 1.1 - 1.3 (Acrobat 2 - 4) + collider-mode'),
(165, 'PDF 1.4 - 1.6 (Acrobat 5 - 8)'),
(166, 'PDF 1.7 Level 3 (Acrobat 9)'),
(167, 'PDF 1.7 Level 8 (Acrobat 10 - 11)'),
(168, 'Password Safe v2'),
(169, 'Password Safe v3'),
(170, 'Lastpass'),
(171, '1Password, agilekeychain'),
(172, '1Password, cloudkeychain'),
(173, 'Bitcoin/Litecoin wallet.dat'),
(174, 'Blockchain, My Wallet');

CREATE TABLE `notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `content` varchar(20000) NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `notes` (`id`, `project_id`, `content`, `when_add`) VALUES
(1, 1, 'Some note 1', 0),
(2, 1, 'Some note 2', 0),
(3, 1, 'Some big note 3 Some big note 3 Some big note 3 Some big note 3 Some big note 3 Some big note 3 Some big note 3 ', 0);

CREATE TABLE `os` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `os` (`id`, `name`) VALUES
(1, 'CentOS'),
(2, 'Cisco IOS'),
(3, 'Debian'),
(4, 'Fedora'),
(5, 'FreeBSD'),
(6, 'Gentoo'),
(7, 'MacOS'),
(8, 'NetBSD'),
(9, 'OpenBSD'),
(10, 'RedHat'),
(11, 'Solaris'),
(12, 'SuSE'),
(13, 'Ubuntu'),
(14, 'Windows Server'),
(15, 'Unknown OS');

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `comment` text NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `projects` (`id`, `name`, `comment`, `when_add`, `updated`) VALUES
(1, 'Demo project', 'Demo company penetration test ', 1473417620, 1473664352);

CREATE TABLE `risk_levels` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `css_class` varchar(20) NOT NULL,
  `sort` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `risk_levels` (`id`, `name`, `css_class`, `sort`) VALUES
(1, 'L_RISK_LOW', 'status-green', 1),
(2, 'L_RISK_MIDDLE', 'status-orange', 2),
(3, 'L_RISK_HIGH', 'status-red', 3);

CREATE TABLE `servers` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `os_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `os_version` varchar(50) NOT NULL,
  `nmap_result` longtext,
  `comment` text NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `when_add` int(10) UNSIGNED NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `servers` (`id`, `project_id`, `ip`, `name`, `os_id`, `os_version`, `nmap_result`, `comment`, `checked`, `when_add`, `updated`) VALUES
(1, 1, '1.1.1.1', 'Router', 9, '6', NULL, '', 0, 1473417686, 1473417872),
(2, 1, '1.1.1.2', 'Web Server', 3, '8.2', NULL, '', 0, 1473417811, 1473417811),
(3, 1, '1.1.1.3', 'Domain Controller', 14, '2012 R2', NULL, '', 0, 1473417890, 1473417890);

CREATE TABLE `servers_software` (
  `id` int(10) UNSIGNED NOT NULL,
  `server_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `version` varchar(45) NOT NULL,
  `version_unknown` tinyint(1) NOT NULL,
  `version_old` tinyint(1) NOT NULL,
  `vendor_site` varchar(150) NOT NULL,
  `banner` varchar(100) NOT NULL,
  `proto` enum('tcp','udp') NOT NULL,
  `port` mediumint(8) UNSIGNED NOT NULL,
  `ghost` tinyint(1) NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `servers_software` (`id`, `server_id`, `name`, `version`, `version_unknown`, `version_old`, `vendor_site`, `banner`, `proto`, `port`, `ghost`, `checked`, `comment`, `updated`, `when_add`) VALUES
(1, 2, 'Apache', '1.3.33', 0, 1, 'http://httpd.apache.org', 'Apache/1.3.33 (Debian)', 'tcp', 80, 0, 0, '', 1473428265, 1473427412),
(2, 2, 'MySQL', '5.5.1', 0, 0, 'http://mysql.com', '', 'tcp', 3306, 0, 1, '', 1473666155, 1473666155),
(3, 2, 'OpenSSH', '6.6.1p1', 0, 0, '', 'SSH2_OpenSSH 6.6.1p1', 'tcp', 22, 0, 0, '', 1473666276, 1473666276);

CREATE TABLE `shells` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shells` (`id`, `name`) VALUES
(1, '----------'),
(2, 'nologin'),
(3, 'ftponly'),
(4, 'Desktop'),
(5, 'bash'),
(6, 'sh'),
(7, 'zsh');

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `object_id` int(10) UNSIGNED NOT NULL,
  `type` enum('project','server','server-software','domain','web-app') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tasks` (`id`, `object_id`, `type`, `name`, `description`, `status`, `updated`, `when_add`) VALUES
(1, 1, 'web-app', 'Bruteforce Admin', 'Try bruteforce Admin login', 3, 1473671261, 1473671261),
(2, 1, 'web-app', 'Download all configs', 'Subj, from router', 1, 1473671294, 1473671294);

CREATE TABLE `tasks_statuses` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `css_class` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tasks_statuses` (`id`, `name`, `css_class`) VALUES
(1, 'Started', 'status-orange'),
(2, 'New', 'status-red'),
(3, 'Done', 'status-green');

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hash_id` bigint(20) UNSIGNED NOT NULL,
  `shell_id` smallint(5) UNSIGNED DEFAULT NULL,
  `home_dir` varchar(255) NOT NULL DEFAULT '',
  `vip` tinyint(1) NOT NULL DEFAULT '0',
  `ghost` tinyint(1) NOT NULL DEFAULT '0',
  `updated` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `group_id`, `login`, `email`, `hash_id`, `shell_id`, `home_dir`, `vip`, `ghost`, `updated`) VALUES
(1, 1, 'Admin', '', 0, NULL, '', 1, 0, 1473418173);

CREATE TABLE `users_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `type` enum('server','server-software','web-app') NOT NULL,
  `object_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `comment` text NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users_groups` (`id`, `type`, `object_id`, `name`, `comment`, `when_add`, `updated`) VALUES
(1, 'web-app', 1, 'Admins', '', 1473418155, 1473418155);

CREATE TABLE `vulns` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('server-software','web-app') NOT NULL,
  `vuln_type_id` smallint(5) UNSIGNED NOT NULL,
  `object_id` int(10) UNSIGNED NOT NULL,
  `risk_level_id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `exploit_link` varchar(1000) NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL,
  `when_add` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vulns` (`id`, `type`, `vuln_type_id`, `object_id`, `risk_level_id`, `name`, `description`, `exploit_link`, `updated`, `when_add`, `sort`) VALUES
(1, 'web-app', 4, 1, 2, 'Bruteforce possibility', 'This software have bruteforce possibility ', 'http://example.com/exp.pl', 1473669990, 1473669288, 2),
(2, 'web-app', 41, 1, 1, 'Banner with OS name', 'Service disclosure OS name and version in self banner', '', 1473669490, 1473669490, 1);

CREATE TABLE `vulns_types` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `type` enum('server-software','web-app') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vulns_types` (`id`, `name`, `type`) VALUES
(1, 'L_VULN_LOGIC_ERROR', 'web-app'),
(2, 'L_VULN_BUFF_OVERFLOW', 'web-app'),
(3, 'L_VULN_RACE_CONDITIONS', 'web-app'),
(4, 'L_VULN_BRUTEFORCE', 'web-app'),
(5, 'L_VULN_WEAK_PASS', 'web-app'),
(6, 'L_VULN_ERR_INPUT_VALIDATION', 'web-app'),
(7, 'L_VULN_ERR_AUTH', 'web-app'),
(8, 'L_VULN_ERR_AUTH2', 'web-app'),
(9, 'L_VULN_DOS', 'web-app'),
(10, 'L_VULN_INSEC_DATA_STORAGE', 'web-app'),
(11, 'L_VULN_MEMORY_LEAK', 'web-app'),
(12, 'L_VULN_CONF_ERR', 'web-app'),
(13, 'L_VULN_CWKV', 'web-app'),
(14, 'L_VULN_XSS', 'web-app'),
(15, 'L_VULN_SQLINJ', 'web-app'),
(16, 'L_VULN_LDAPINJ', 'web-app'),
(17, 'L_VULN_XPATHINJ', 'web-app'),
(18, 'L_VULN_CRLFINJ', 'web-app'),
(19, 'L_VULN_DIR_BYPASS', 'web-app'),
(20, 'L_VULN_PHP_LFI', 'web-app'),
(21, 'L_VULN_PHP_RFI', 'web-app'),
(22, 'L_VULN_PHP_OBJ_INJ', 'web-app'),
(23, 'L_VULN_PHP_LFR', 'web-app'),
(24, 'L_VULN_PHP_RFR', 'web-app'),
(25, 'L_VULN_XXE', 'web-app'),
(26, 'L_VULN_UNV_REDIRECTS', 'web-app'),
(27, 'L_VULN_LOGIC_ERROR', 'server-software'),
(28, 'L_VULN_BUFF_OVERFLOW', 'server-software'),
(29, 'L_VULN_RACE_CONDITIONS', 'server-software'),
(30, 'L_VULN_BRUTEFORCE', 'server-software'),
(31, 'L_VULN_WEAK_PASS', 'server-software'),
(32, 'L_VULN_ERR_INPUT_VALIDATION', 'server-software'),
(33, 'L_VULN_ERR_AUTH', 'server-software'),
(34, 'L_VULN_ERR_AUTH2', 'server-software'),
(35, 'L_VULN_DOS', 'server-software'),
(36, 'L_VULN_INSEC_DATA_STORAGE', 'server-software'),
(37, 'L_VULN_MEMORY_LEAK', 'server-software'),
(38, 'L_VULN_CONF_ERR', 'server-software'),
(39, 'L_VULN_CWKV', 'server-software'),
(40, 'L_VULN_INF_DISC', 'server-software'),
(41, 'L_VULN_INF_DISC', 'web-app');

CREATE TABLE `web_apps` (
  `id` int(10) UNSIGNED NOT NULL,
  `domain_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `version` varchar(45) NOT NULL,
  `version_unknown` tinyint(1) NOT NULL,
  `version_old` tinyint(1) NOT NULL,
  `vendor_site` varchar(150) NOT NULL,
  `need_auth` tinyint(1) NOT NULL,
  `url_rewrite` tinyint(1) NOT NULL,
  `ghost` tinyint(1) NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `updated` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `web_apps` (`id`, `domain_id`, `name`, `url`, `version`, `version_unknown`, `version_old`, `vendor_site`, `need_auth`, `url_rewrite`, `ghost`, `checked`, `comment`, `updated`) VALUES
(1, 3, 'AdminPanel', '/', '0.9', 0, 1, '', 1, 0, 0, 0, 'Router admin interface', 1473418038);


ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `server_id` (`server_id`,`name`),
  ADD KEY `fk_domains_grpid_idx` (`server_id`);

ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `hashes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_type` (`user_id`),
  ADD KEY `fk_hashes_alg_idx` (`alg_id`),
  ADD KEY `cracked` (`cracked`),
  ADD KEY `hash` (`hash`,`salt`,`password`,`cracked`) USING BTREE;

ALTER TABLE `hash_algs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `os`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `risk_levels`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`name`),
  ADD UNIQUE KEY `project_id_2` (`project_id`,`ip`),
  ADD KEY `fk_servers_os_idx` (`os_id`),
  ADD KEY `group` (`project_id`),
  ADD KEY `checked` (`checked`);

ALTER TABLE `servers_software`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`);

ALTER TABLE `shells`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

ALTER TABLE `tasks_statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servers_users_groups_idx` (`group_id`),
  ADD KEY `vip` (`vip`);

ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servers_users_groups_srvid_idx` (`object_id`);

ALTER TABLE `vulns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_server_software_vulns_typeid_idx` (`vuln_type_id`),
  ADD KEY `fk_server_software_id_idx` (`object_id`),
  ADD KEY `fk_server_software_vulns_riskid_idx` (`risk_level_id`),
  ADD KEY `sort` (`sort`);

ALTER TABLE `vulns_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

ALTER TABLE `web_apps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_domains_apps_dmnid_idx` (`domain_id`);


ALTER TABLE `domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `hashes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `hash_algs`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `os`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `risk_levels`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `servers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `servers_software`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `shells`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `tasks_statuses`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `vulns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `vulns_types`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `web_apps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

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