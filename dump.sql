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

INSERT INTO `posts` (`id`, `author_id`, `state`, `lang`, `date`, `meta_keywords`, `meta_description`, `url`, `title`, `perex`, `body`) VALUES
(1,	1,	1,	1,	'1299539173',	'',	'',	'pan-golombek-a-pan-valenta',	'Pan Golombel a pan Valenta',	'',	'Bylo horké redakční léto, kdy se nic, ale zhola nic neděje, kdy se nedělá politika a kdy není ani žádná evropská situace; a přece i v tuto dobu čtenáři novin, ležící v agónii nudy na březích vod nebo v řídkém stínu stromů, zdemoralizovaní vedrem, přírodou, venkovským klidem a vůbec zdravým a prostým životem na dovolené, čekají s denně zklamávanou nadějí, že aspoň v těch novinách bude něco nového a osvěžujícího, nějaká vražda nebo válka nebo zemětřesení, zkrátka Něco; a když to tam není, tlukou novinami a roztrpčeně prohlašují, že v těch novinách nic, ale docela Nic není a že to vůbec nestojí za čtení a že už to nebudou odebírat.\r\nA zatím v redakci sedí pět nebo šest opuštěných lidí, neboť ostatní kolegové jsou také na dovolené, kde tlukou rozhořčeně novinami a stěžují se, že teď v těch novinách nic, ale docela Nic není. A ze sazárny vyjde pan metér a povídá vyčítavě: “Páni, páni, ještě nemáme na zítřek úvodník.”\r\n“Tak tam třeba dejte… ten článek… o hospodářské situaci v Bulharsku,” míní jeden z opuštěných pánů.\r\nPan metér těžce vzdychne: “Ale kdo to má číst, pane redaktore? Už zase v celém listě nebude Nic ke Čtení.”\r\nŠest opuštěných pánů zvedne oči ke stropu, jako by tam bylo možno objevit Něco ke Čtení.\r\n“Kdyby se takhle Něco stalo,” navrhuje jeden neurčitě.\r\n“Nebo mít… nějakou… zajímavou reportáž,” nadhazuje druhý.\r\n“O čem?”\r\n“To nevím.”'),
(2,	1,	1,	1,	'1299539209',	'',	'',	'prvni-dojmy',	'První dojmy',	'',	'“Musí se začínat od začátku,” radil mi kdysi mistr Chauliac; ale jelikož jsem už deset dní na tomto babylónském ostrově, ztratil se mi začátek. Čím mám nyní začít? Opečeným špekem nebo výstavou ve Wembley? Panem Shawem nebo londýnskými strážníky? Vidím, že začínám velmi zmateně; ale co se týče těch strážníků, musím říci, že jsou rekrutováni podle krásy a velikosti; jsou jako bohové, o hlavu větší než lidé smrtelní, a jejich moc je neomezená; když takový dvoumetrový Bob na Piccadilly zvedne ruku, zastaví se všechny vehikly, utkví Saturn a Uran stane na své nebeské dráze čekaje, až Bob tu ruku spustí. Nikdy jsem neviděl něco tak nadlidského.\r\n\r\nNejvětší překvapení cestovatele je, najde-li v cizí zemi to, o čem stokrát četl nebo co stokrát viděl na obrázku. Užasl jsem, když jsem v Miláně našel milánský dóm nebo Koloseum v Římě. Je to poněkud příšerný dojem, protože člověk má pocit, že už tu někdy byl nebo že to už jednou nějak zažil, snad ve snu nebo kdy. Zarazí tě, že v Holandsku jsou opravdu větrné mlýny a kanály a že na londýnském Strandu je opravdu tolik lidí, že ti je z toho špatně. Jsou dva zcela fantastické dojmy: nalézt něco neočekávaného a nalézt něco hodně známého. Člověk se vždycky nahlas podiví, když zničehonic potká starého známého. Nuže, stejně jsem se podivil, když jsem nad Temží našel Parliament, na ulicích gentlemany v šedivých cylindrech, na křižovatkách dvoumetrové Boby a tak dále. Byl to překvapující objev, že Anglie je opravdu anglická.\r\n\r\nAle abych přece jen začal od začátku, tedy nakreslil jsem vám obrázek, jak vypadá Anglie, když se k ní blížíte z Kanálu. To bílé jsou prostě skály a nahoře roste tráva; je to sice stavěno dosti důkladně, tak říkajíc na skále, ale mít pod nohama kontinent, lidi, to je přece jen solidnější pocit.'),
(3,	1,	1,	2,	'1299539321',	'',	'',	'php',	'PHP',	'',	'/---code php\r\n  function reImage($matches) {\r\n    $content = $matches[1];\r\n    $align = $matches[5];\r\n    $href = $matches[6];\r\n  }\r\n\\---'),
(7,	1,	1,	1,	'1299760831',	'',	'',	'k-zloti-broun-sous',	'K žloti broun sous',	'',	'K žloti broun sousk roniflu děd plezoutěz zlop lkůby. Titididlo niťtist, clo a tilke tikte zruň řouniď já dědlu pa pizko dre. Těmo tiš dinimlíž bodlimrelá dludidlýčle nědě děst niplé pochluň s chluděň. Dim vedi diněpliť i tim mykre puhe řeh utinist ně k zlegré. člip stou. Jímr luh. V kůti vup křoubo clytry o zlam. Nuhy hrulněn stej diněn mestou těvyplůj a bry nidě meňadéb šlině z tiskama. Utiv s těhléch věně i něp šlyštčlucru těti hřobým bo mlodě ktani. Z uby a dymebuťcliť vitětěchlež něš s gryga ně řid ukřou. Dést šlávrů cruně děmřitřád. Fleskatěn v hloup zrébiš úpiv dyvani my ceštodrnic. I chru timlipevo chrá slinir lkázblávěť škoste mlij slou? Lkeploukrůmon a prymlu ňaď zrébim vyc něflý a tiklu. Flůmydi těbus milin bečložo veninslu tětřu, i dá. Pařo gla oni skyh o vumědý didiši mymlék mřapo? V gouvo glašt něpří bráti hraletří.\r\n\r\n/---code php\r\n\r\n   /**\r\n	* Delete post form clicked.\r\n	* \r\n	*/\r\n	public function deletePostFormSubmitted(NAppForm $form)\r\n	{\r\n		if ($form[\'delete\']->isSubmittedBy()) {\r\n			$post = new Posts;\r\n			$post->delete($this->getParam(\'id\'));\r\n			$this->flashMessage(\'Post deleted!\');\r\n		}\r\n\r\n		$this->redirect(\'AdminPosts:archives\');\r\n	}\r\n\r\n\\---\r\n\r\nOmy dišt krumé ktýžluče timrušké bihrou ticháchtime a vot, žrovr něklež chrouč. Glich puhemlu ňoba flyd otou skadru přotivr. Mroč škušláš zlor blumý, omo ciniklaď oni. Bodiš žeď opa prékro. Ti vu. úfroš pryš. Dimipu py mod crotěm vevlaňně něpta krůď tim pymruzizkřo. Jomroňoud opřudich déď nivosrýfi něs tichla člouňo tisk pyž. Crysly o lkást břaďou němřo těbyměč. Sýzras ptoj a usy mumřo pih mruchle myt citřou z pacreněmře. Uslamě vetry něštni vop dihr.\r\n\r\nTkůčluzretě těmle sliřhroužlyc ma těmiché klytě bredi ktá drt nisto. Třepůclo naťžly pudro a pud vynýtěšká. úbroně těvyž žryž s fletěd, člouštti bos pryněr zlodi. O hřálka draš peř klůdrá těro i lípá. Mřozy těm čloť můblom dich. Byst bušta vřozoť ho slarni didi něsk. Grýtě vlovaz mí chrulá fopti těp lke.'),
(8,	2,	1,	2,	'1299763508',	'',	'',	'hello-world',	'Hello World!',	'',	'How are you today?\r\n\r\n/---code javascript\r\ndocument.getElementById(\'hello\').innerHTML = \'World\';\r\n\\---'),
(9,	2,	1,	1,	'1299766388',	'meta keyw',	'meta desc',	'k-zloti-broun',	'K žloti broun',	'K žloti broun sousk roniflu děd plezoutěz zlop lkůby. Titididlo niťtist, clo a tilke tikte zruň řouniď já dědlu pa pizko dre. Těmo tiš dinimlíž bodlimrelá dludidlýčle nědě děst niplé pochluň s chluděň. Dim vedi diněpliť i tim mykre puhe řeh utinist ně k zlegré. člip stou. Jímr luh. V kůti vup křoubo clytry o zlam. Nuhy hrulněn stej diněn mestou těvyplůj a bry nidě meňadéb šlině z tiskama. Utiv s těhléch věně i něp šlyštčlucru těti hřobým bo mlodě ktani. Z uby a dymebuťcliť vitětěchlež něš s gryga ně řid ukřou. Dést šlávrů cruně děmřitřád.',	'Fleskatěn v hloup zrébiš úpiv dyvani my ceštodrnic. I chru timlipevo chrá slinir lkázblávěť škoste mlij slou? Lkeploukrůmon a prymlu ňaď zrébim vyc něflý a tiklu. Flůmydi těbus milin bečložo veninslu tětřu, i dá. Pařo gla oni skyh o vumědý didiši mymlék mřapo? V gouvo glašt něpří bráti hraletří.\r\n\r\n/---code php\r\n\r\n   /**\r\n	* Delete post form clicked.\r\n	* \r\n	*/\r\n	public function deletePostFormSubmitted(NAppForm $form)\r\n	{\r\n		if ($form[\'delete\']->isSubmittedBy()) {\r\n			$post = new Posts;\r\n			$post->delete($this->getParam(\'id\'));\r\n			$this->flashMessage(\'Post deleted!\');\r\n		}\r\n\r\n		$this->redirect(\'AdminPosts:archives\');\r\n	}\r\n\r\n\\---\r\n\r\nOmy dišt krumé ktýžluče timrušké bihrou ticháchtime a vot, žrovr něklež chrouč. Glich puhemlu ňoba flyd otou skadru přotivr. Mroč škušláš zlor blumý, omo ciniklaď oni. Bodiš žeď opa prékro. Ti vu. úfroš pryš. Dimipu py mod crotěm vevlaňně něpta krůď tim pymruzizkřo. Jomroňoud opřudich déď nivosrýfi něs tichla člouňo tisk pyž. Crysly o lkást břaďou němřo těbyměč. Sýzras ptoj a usy mumřo pih mruchle myt citřou z pacreněmře. Uslamě vetry něštni vop dihr.\r\n\r\nTkůčluzretě těmle sliřhroužlyc ma těmiché klytě bredi ktá drt nisto. Třepůclo naťžly pudro a pud vynýtěšká. úbroně těvyž žryž s fletěd, člouštti bos pryněr zlodi. O hřálka draš peř klůdrá těro i lípá. Mřozy těm čloť můblom dich. Byst bušta vřozoť ho slarni didi něsk. Grýtě vlovaz mí chrulá fopti těp lke.'),
(10,	1,	1,	2,	'1299780212',	'',	'',	'hello-world',	'Hello World!',	'Hello World! aa',	'Hello World! aa'),
(11,	1,	2,	1,	'1300372828',	'sdc',	'cd',	'',	'',	'',	'scdcsdcs sdc sdc s'),
(12,	1,	2,	1,	'1300373431',	'',	'',	'',	'',	'',	' Jímr luh. V kůti vup křoubo clytry o zlam. Nuhy hrulněn stej diněn mestou těvyplůj a bry nidě meňadéb šlině z tiskama. Utiv s těhléch věně i něp šlyštčlucru těti hřobým bo mlodě ktani. Z uby a dymebuťcliť vitětěchlež něš s gryga ně řid ukřou. Dést šlávrů cruně děmřitřád.');

DROP TABLE IF EXISTS `posts_tags`;
CREATE TABLE `posts_tags` (
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `posts_tags` (`tag_id`, `post_id`) VALUES
(27,	1),
(28,	1),
(27,	2),
(29,	2),
(30,	3),
(31,	4),
(31,	5),
(32,	5),
(32,	6),
(30,	7),
(29,	7),
(33,	8),
(34,	8),
(30,	9),
(35,	10),
(35,	11),
(30,	11),
(33,	11),
(30,	12),
(29,	13);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `settings` (`name`, `value`) VALUES
('web_name',	'hyperon blog engine'),
('web_desc',	'Lorem ipsum dolor sit amet');

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `tag_url` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `tags` (`id`, `tag`, `tag_url`) VALUES
(31,	'fdbgfdgb',	'fdbgfdgb'),
(30,	'PHP',	'php'),
(32,	'ěščěšč ěščěšč',	'escesc-escesc'),
(29,	'Anglické listy',	'anglicke-listy'),
(28,	'Válka s mloky',	'valka-s-mloky'),
(27,	'Čapek',	'capek'),
(33,	'lupuli',	'lupuli'),
(34,	'kapuli',	'kapuli'),
(35,	'',	'');

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

INSERT INTO `users` (`id`, `username`, `realname`, `email`, `avatar`, `about`, `password`, `twitter_user_id`, `twitter_token`, `twitter_token_secret`, `facebook_user_id`, `facebook_sig`, `facebook_access_token`, `role`) VALUES
(1,	'Langi',	'Peter Láng',	'cz.lang@gmail.com',	'peter-lang_avatar.jpg',	'',	'43d970f320e27d6c2924aa142322f8adda17dcc6',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'admin'),
(2,	'demo',	'demo',	'demo',	'',	'',	'5863d9e4cbdf522eaa62e0747fceb1c5b249ba13',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'admin');

-- 2011-03-17 16:05:46