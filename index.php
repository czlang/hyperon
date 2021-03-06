<?php

// the identification of this site
define('SITE', '');

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/libs');

// absolute filesystem path to the temporary files
define('TEMP_DIR', WWW_DIR . '/temp');

// absolute filesystem path to the temporary files
define('LOG_DIR', WWW_DIR . '/log');

// load bootstrap file
require APP_DIR . '/bootstrap.php';