-- Adminer 3.3.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `autosave`;
CREATE TABLE `autosave` (
  `post_id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=dec8 COLLATE=dec8_bin;

INSERT INTO `autosave` (`post_id`, `text`) VALUES
(1, 'Vypadajícího ženy býk originality rus mě ví řadě dar ví? Nedopatřením chuť pán. U ho návladním ty ode rohu jí němý dá hodní čúrkem měly u barvené dům zachvěl plulo firma vraceje sad 27451 vzdát. O žiju jé vypan půl kapitola? Nad 77 díže sekta radosti příliš jaterní. Dá ně rašple kraj uzavřel jací z vyhodí ať nastalo čí přijali, zadečku čí bys pral zvykl no došel rozluštil, až roli vystihl bohu irmu jel hadr – myslet až div nevěsty bůhvíjak slamníku. Díků po hrboly a dbá.\n\nVáš dary fuk sehnutý ti kvočna, čí řadě návštěv ty jasnosti, teplých sil řek stráži ostříhané chybí, jí utrpení si odvahu kterýkoliv levém sekýroval. Ně mu sykal matka ex lišácky. Náš spolkl třetí s hlouposti. Hudby povstala deltoides, verše ho paralýza, že báli hm. Oči ti au velikému.\n\nDá báli píší pugét hébé žádnou k za zrada činného. Pitra určil no hořelo do zámcích a mocí krk milé skrz matko věř ke přivedu u prve paříže lid kufr vy ven obšírný au granát podívala jít lež zvednout noví jen. Pece dokonce tu míří nejen kterému u zlaté hm co. Oč mít dnešní loď filmu skok s obědu poplakali učiteli, růžově volná z panička čtyř generace. Arenelle má ví dle via oběhl hůlka vchodu. Po proč připomíná ba sbírkou dílny ó řízení úctu ve toto jo.\n\nHasit pohoršující háj aura tebe vypukl výrok přistěhovalec mladého svět šófl a pot korunku ze vrhač šťastný, jí ho kdes vystřelil těmhle. Čerta dvě ji neříkejte ukradeném nestoudný. Kód haló vytahuje hodí, ono tloukl hohe. Půda jedna vhodnou radu ne ať krásných má oběť pokutu rusy odemkl bytu sotva – ty ne kdo přineseme zanelli staral – he lepší zahučel osmi hněvu spolehlivé no dbát bezprostředně. Uf ze, dávky už řek čtvrti he službě, ó ne usně čtu historii má? Třas věcmi a moct zavřený úsvit špitále třikrát au koukáte.\n\nPíseň franta musí víš jejich vynoří. Jím přejí kočka pozná v svíjela hrdlo. Očí tě nač ptáčka dodávala lázní. Bys fair cenu dělejte řemeslo slibovali ó viď o lakoto té kvalt, boršovskému měl katapult zatvrzují. Pot až pruh drahy omylem z ano hovořilino.\n\nVe on posluha, sem míč ni ah zle té lupa nerozeznáte možné myšlenka! Rvačka to kontoru vzrušené strážníkem vesnice k toto porady, šestadvacítka kuse, ty držte po šedá z obíral vší běžící, věř žádá odhodil kruci, najměte vyfásneš, melocactus a chodník o metru si kost vagóně. Hráz 81 jé získali z pitomou až hořelo po kříži, řek tomsovi janu památka, zařídil, oblečená, zločincem ujel, rozhodnut s mužů čermákovi advokátských podrobností brumlal. 11 hoch jo mi nalitého, dále, kradl věc doslovně psaním, ó vpadl led hm něčí. By ni vykonat my lov moh ni ať, no tě ní anonymním. My té váhavě, chryzantémou dobrodružství uf zastřelí musíte poeta vynikajícím. Žen mohu chvěje.\n');

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `author` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `body` text COLLATE utf8_czech_ci NOT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `comments` (`id`, `post_id`, `parent_id`, `depth`, `sort`, `time`, `author`, `user_id`, `body`, `visible`) VALUES
(1, 1,  0,  1,  1,  '2012-05-15 22:24:18',  'Komentátor', 1,  'Tohle je testovací komentář k testovacímu článku', 1),
(3, 1,  0,  1,  2,  '2012-05-15 22:24:21',  'Další komentátor', 1,  'A tohle je další komentář, trochu delší: \n\n\nPíseň franta musí víš jejich vynoří. Jím přejí kočka pozná v svíjela hrdlo. Očí tě nač ptáčka dodávala lázní. Bys fair cenu dělejte řemeslo slibovali ó viď o lakoto té kvalt, boršovskému měl katapult zatvrzují. Pot až pruh drahy omylem z ano hovořilino.\n\nVe on posluha, sem míč ni ah zle té lupa nerozeznáte možné myšlenka! Rvačka to kontoru vzrušené strážníkem vesnice k toto porady, šestadvacítka kuse, ty držte po šedá z obíral vší běžící, věř žádá odhodil kruci, najměte vyfásneš, melocactus a chodník o metru si kost vagóně. Hráz 81 jé získali z pitomou až hořelo po kříži, řek tomsovi janu památka, zařídil, oblečená, zločincem ujel, rozhodnut s mužů čermákovi advokátských podrobností brumlal. 11 hoch jo mi nalitého, dále, kradl věc doslovně psaním, ó vpadl led hm něčí. By ni vykonat my lov moh ni ať, no tě ní anonymním. My té váhavě, chryzantémou dobrodružství uf zastřelí musíte poeta vynikajícím. Žen mohu chvěje.\n',  1),
(4, 1,  3,  2,  3,  '2012-05-15 22:28:40',  'Reagující komentátor', 1,  'Tohle je reakce na Dalšího komentátora', 1),
(5, 1,  4,  3,  4,  '2012-05-15 22:29:20',  'Další reagující komentátor', 1,  'Reakce na reagujícího komentátora',  1),
(6, 1,  0,  1,  5,  '2012-05-15 22:30:58',  'Franta Vokurka', 1,  'Franta k tomu taky má co říct',  1);

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '1 - post, 2 - draft',
  `date` datetime NOT NULL,
  `url` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `body` text COLLATE utf8_czech_ci NOT NULL,
  `comments_disabled` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `body` (`body`),
  FULLTEXT KEY `title_2` (`title`,`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `posts` (`id`, `author_id`, `state`, `date`, `url`, `title`, `body`, `comments_disabled`) VALUES
(1, 1,  1,  '2012-05-15 22:10:49',  'nedopatrenim-chut-pan',  'Nedopatřením chuť pán',  'Vypadajícího ženy býk originality rus mě ví řadě dar ví? Nedopatřením chuť pán. U ho návladním ty ode rohu jí němý dá hodní čúrkem měly u barvené dům zachvěl plulo firma vraceje sad 27451 vzdát. O žiju jé vypan půl kapitola? Nad 77 díže sekta radosti příliš jaterní. Dá ně rašple kraj uzavřel jací z vyhodí ať nastalo čí přijali, zadečku čí bys pral zvykl no došel rozluštil, až roli vystihl bohu irmu jel hadr – myslet až div nevěsty bůhvíjak slamníku. Díků po hrboly a dbá.\r\n\r\nVáš dary fuk sehnutý ti kvočna, čí řadě návštěv ty jasnosti, teplých sil řek stráži ostříhané chybí, jí utrpení si odvahu kterýkoliv levém sekýroval. Ně mu sykal matka ex lišácky. Náš spolkl třetí s hlouposti. Hudby povstala deltoides, verše ho paralýza, že báli hm. Oči ti au velikému.\r\n\r\nDá báli píší pugét hébé žádnou k za zrada činného. Pitra určil no hořelo do zámcích a mocí krk milé skrz matko věř ke přivedu u prve paříže lid kufr vy ven obšírný au granát podívala jít lež zvednout noví jen. Pece dokonce tu míří nejen kterému u zlaté hm co. Oč mít dnešní loď filmu skok s obědu poplakali učiteli, růžově volná z panička čtyř generace. Arenelle má ví dle via oběhl hůlka vchodu. Po proč připomíná ba sbírkou dílny ó řízení úctu ve toto jo.\r\n\r\nHasit pohoršující háj aura tebe vypukl výrok přistěhovalec mladého svět šófl a pot korunku ze vrhač šťastný, jí ho kdes vystřelil těmhle. Čerta dvě ji neříkejte ukradeném nestoudný. Kód haló vytahuje hodí, ono tloukl hohe. Půda jedna vhodnou radu ne ať krásných má oběť pokutu rusy odemkl bytu sotva – ty ne kdo přineseme zanelli staral – he lepší zahučel osmi hněvu spolehlivé no dbát bezprostředně. Uf ze, dávky už řek čtvrti he službě, ó ne usně čtu historii má? Třas věcmi a moct zavřený úsvit špitále třikrát au koukáte.\r\n\r\nPíseň franta musí víš jejich vynoří. Jím přejí kočka pozná v svíjela hrdlo. Očí tě nač ptáčka dodávala lázní. Bys fair cenu dělejte řemeslo slibovali ó viď o lakoto té kvalt, boršovskému měl katapult zatvrzují. Pot až pruh drahy omylem z ano hovořilino.\r\n\r\nVe on posluha, sem míč ni ah zle té lupa nerozeznáte možné myšlenka! Rvačka to kontoru vzrušené strážníkem vesnice k toto porady, šestadvacítka kuse, ty držte po šedá z obíral vší běžící, věř žádá odhodil kruci, najměte vyfásneš, melocactus a chodník o metru si kost vagóně. Hráz 81 jé získali z pitomou až hořelo po kříži, řek tomsovi janu památka, zařídil, oblečená, zločincem ujel, rozhodnut s mužů čermákovi advokátských podrobností brumlal. 11 hoch jo mi nalitého, dále, kradl věc doslovně psaním, ó vpadl led hm něčí. By ni vykonat my lov moh ni ať, no tě ní anonymním. My té váhavě, chryzantémou dobrodružství uf zastřelí musíte poeta vynikajícím. Žen mohu chvěje.\r\n',  0);

DROP TABLE IF EXISTS `posts_tags`;
CREATE TABLE `posts_tags` (
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `posts_tags` (`tag_id`, `post_id`) VALUES
(1, 1),
(2, 1);

DROP TABLE IF EXISTS `search_log`;
CREATE TABLE `search_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_query` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `search_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `settings` (`name`, `value`) VALUES
('web_name',  'My Blog'),
('web_desc',  ''),
('web_email', ''),
('meta_description',  ''),
('meta_keywords', ''),
('template',  'default'),
('google_analytics_id', ''),
('comments_enabled',  '1'),
('html_head', ''),
('socials', ''),
('tw_social', ''),
('fb_social', ''),
('gplus_social',  ''),
('about', '');

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `tag_url` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `tags` (`id`, `tag`, `tag_url`) VALUES
(1, 'Tag',  'tag'),
(2, 'Ještě jeden',  'jeste-jeden');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `realname` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `about` text COLLATE utf8_czech_ci,
  `password` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `twitter_user_id` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `twitter_token` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `twitter_token_secret` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_user_id` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_sig` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `facebook_access_token` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `users` (`id`, `username`, `realname`, `email`, `avatar`, `about`, `password`, `twitter_user_id`, `twitter_token`, `twitter_token_secret`, `facebook_user_id`, `facebook_sig`, `facebook_access_token`, `role`) VALUES
(1, NULL, 'Peter Láng', 'cz.lang@gmail.com',  '', '', '43d970f320e27d6c2924aa142322f8adda17dcc6', NULL, NULL, NULL, NULL, NULL, NULL, 'admin');

-- 2012-05-15 22:32:57