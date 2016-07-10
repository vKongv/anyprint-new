-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `printer`;
CREATE TABLE `printer` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Printer ID',
  `g_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Identification number for printer from GCP',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Printer Name',
  `status` smallint(6) NOT NULL COMMENT 'Printer''s Status',
  `color_option` smallint(6) NOT NULL COMMENT 'Printer''s color capability',
  `deleted` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Delete Indicator for printer',
  `printin_shop_id` int(6) NOT NULL COMMENT 'Printing Shop ID that own this printer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `g_id` (`g_id`),
  KEY `printin_shop_id` (`printin_shop_id`),
  CONSTRAINT `printer_ibfk_1` FOREIGN KEY (`printin_shop_id`) REFERENCES `printing_shop` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `printer` (`id`, `g_id`, `name`, `status`, `color_option`, `deleted`, `printin_shop_id`) VALUES
(200001,	'07360666-3e0b-4452-1aa5-1dbfd85d58ff',	'Canon E510 series',	0,	1,	1,	100001),
(200002,	'eaf39eba-113f-0f5a-7b76-5cba4ec6c386',	'Canon E510 series',	0,	1,	0,	100001);

DROP TABLE IF EXISTS `printing_shop`;
CREATE TABLE `printing_shop` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Printing Shop ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Printing Shop Name',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Printing Shop Actual Address',
  `area` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'State of the printing shop located',
  `operating_hour` time NOT NULL COMMENT 'Printing Shop Open Time',
  `closing_hour` time NOT NULL COMMENT 'Printing Shop Close Time',
  `verification_code` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Verification code for printing shop',
  `user_id` int(6) NOT NULL COMMENT 'User ID that own this shop',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `printing_shop_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `printing_shop` (`id`, `name`, `address`, `area`, `operating_hour`, `closing_hour`, `verification_code`, `user_id`) VALUES
(100001,	'Test Printing Shop',	'196, Jalan Tung Yen, 72000 Kuala Pilah, Negeri Sembilan',	'Melaka',	'00:00:00',	'23:59:00',	'9408',	400002);

DROP TABLE IF EXISTS `print_request`;
CREATE TABLE `print_request` (
  `id` int(6) NOT NULL COMMENT 'Print Request ID',
  `job_id` varchar(64) NOT NULL COMMENT 'Job ID in GCP',
  `name` varchar(255) NOT NULL COMMENT 'Name of the document',
  `status` varchar(32) NOT NULL COMMENT 'Print Request Status',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Print Request Submit Time',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Price of the print request',
  `verification_code` int(4) NOT NULL COMMENT 'Verification Code for the print request',
  `printer_id` int(6) NOT NULL COMMENT 'Printer ID that receiving the job',
  `user_id` int(6) NOT NULL COMMENT 'User ID that submitting the job',
  PRIMARY KEY (`id`),
  KEY `printer_id` (`printer_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `print_request_ibfk_1` FOREIGN KEY (`printer_id`) REFERENCES `printer` (`id`),
  CONSTRAINT `print_request_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `print_request` (`id`, `job_id`, `name`, `status`, `created_at`, `price`, `verification_code`, `printer_id`, `user_id`) VALUES
(300001,	'f1c73dba-ccad-dca8-e0be-208e7d48ae30',	'resume_vivien.pdf',	'COMPLETED',	'2016-04-24 15:30:18',	10.00,	7232,	200002,	400001),
(300002,	'a9c28754-66de-7182-3c92-fe9b1dadca76',	'Receipt.pdf',	'IN_PROGRESS',	'2016-04-25 01:00:57',	0.00,	6749,	200002,	400001),
(300003,	'72454867-89d1-04ad-ff3b-8e57ce4ca591',	'n6475_graduate_bootcamp_local_1.pdf',	'DONE',	'2016-04-25 01:12:06',	0.00,	4985,	200002,	400001),
(300004,	'0ddb4846-57a4-1c11-2b0d-1c92c515e44d',	'n6475_launchpad_company_profile.pdf',	'DONE',	'2016-04-25 02:06:45',	0.00,	7332,	200002,	400001),
(300005,	'c9db76cf-86d2-5ce2-ac88-0d02f5a5ae6f',	'yesBill.pdf',	'DONE',	'2016-04-25 07:14:35',	0.00,	8385,	200002,	400001);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Name',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Authentication Key',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Password Hash',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User Password Reset Token',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Password',
  `hp` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Handphone',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User Email',
  `status` smallint(6) NOT NULL DEFAULT '-1' COMMENT 'User Status',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT 'User Type',
  `created_at` int(11) NOT NULL COMMENT 'Created Time',
  `updated_at` int(11) NOT NULL COMMENT 'Updated Time',
  `google_refresh_token` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Business) Google OAuth2.0 Refresh Token',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`,`hp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `password`, `hp`, `email`, `status`, `type`, `created_at`, `updated_at`, `google_refresh_token`) VALUES
(400001,	'kong',	'',	'',	NULL,	'12345678',	'0173380115',	'test@test.com',	0,	0,	0,	0,	''),
(400002,	'testprintingshop',	'',	'',	NULL,	'12345678',	'0179556908',	'test@testprint.com',	0,	0,	0,	0,	'1/7pnitmnxFnJlpZzRJKSB1V8P9ZU5txo5ZEz2BbEhb18'),
(400003,	'Kong Ka Weng',	'',	'test',	NULL,	'test',	'0173380115',	'mgongzai@gmail.com',	0,	0,	0,	0,	'-');

-- 2016-07-09 17:14:59
