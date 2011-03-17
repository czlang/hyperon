-- Adminer 3.2.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '1 - post, 2 - beep, 3 - draft',
  `lang` tinyint(1) NOT NULL COMMENT '1 - czech, 2 - english',
  `date` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `perex` text COLLATE utf8_czech_ci NOT NULL,
  `body` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `post_title` (`title`,`body`),
  FULLTEXT KEY `post_title_2` (`title`,`body`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `posts_tags`;
CREATE TABLE `posts_tags` (
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;



DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `tag_url` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;



DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `realname` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `about` text COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `twitter_user_id` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `twitter_token` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `twitter_token_secret` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_user_id` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_sig` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_access_token` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2011-03-17 16:05:46