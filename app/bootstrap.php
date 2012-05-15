<?php

/**
 * My NApplication bootstrap file.
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */



// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/Nette/loader.php';



// Step 2: Configure environment
// 2a) enable NDebug for better exception and error visualisation
NDebug::enable();
NDebug::$strictMode = TRUE;
NDebug::$showBar = TRUE;
//NDebug::$email = '';


//NDebug::$maxDepth = 10;  // hloubka zanoření polí
//NDebug::$maxLen   = 999999; // maximální délka řetězce

// 2b) load configuration from config.ini file
NEnvironment::loadConfig();



// 2c) setup sessions
$session = NEnvironment::getSession();
$session->setSavePath(WWW_DIR . '/sessions/');
$session->start();

$user = NEnvironment::getUser();
$user->setExpiration("+ 365 days", FALSE);
//$user->setExpiration(0, false);



// Step 3: Configure application
// 3a) get and setup a front controller
$application = NEnvironment::getApplication();

$application->errorPresenter = 'Error';
if(NEnvironment::isProduction()) $application->catchExceptions = TRUE;
   else $application->catchExceptions = FALSE;
// $application->catchExceptions = TRUE;


$application->onStartup[] = 'Users::initialize';


// Step 4: Setup application router
$router = $application->getRouter();



//$router[] = new NRoute('/<username>', 'Products:user');

$router[] = new NRoute('admin/', array(
	'presenter' => 'AdminPosts',
	'action' => 'newpost',
));

$router[] = new NRoute('hledat/', array(
	'presenter' => 'Search',
	'action' => 'result',
));
$router[] = new NRoute('archiv/strana/<vp-page>', array(
	'presenter' => 'Posts',
	'action' => 'archives',
	'id' => 'vp-page',
));
$router[] = new NRoute('archiv/', array(
	'presenter' => 'Posts',
	'action' => 'archives',	
));

$router[] = new NRoute('tag/<tag_url>/strana/<vp-page>', array(
	'presenter' => 'Posts',
	'action' => 'tag',
	'id' => array('tag_url', 'vp-page'),
));
$router[] = new NRoute('strana/<vp-page>', array(
	'presenter' => 'Homepage',
	'action' => 'default',
	'id' => 'vp-page',
));
$router[] = new NRoute('feed/rss', array(
	'presenter' => 'Feed',
	'action' => 'default',
));
$router[] = new NRoute('register', array(
	'presenter' => 'Register',
	'action' => 'default',
));
$router[] = new NRoute('<post_url>', array(
	'presenter' => 'Posts',
	'action' => 'post',
	'id' => 'url',
));
$router[] = new NRoute('tag/<tag_url>', array(
	'presenter' => 'Posts',
	'action' => 'tag',
	'id' => 'tag_url',
));





$router[] = new NSimpleRouter('Homepage:default');

// Step 5: Run the application!
$application->run();



