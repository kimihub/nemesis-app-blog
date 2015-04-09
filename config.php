<?php
/***************************************************************************/
/* Author: Kimi */
/* App Version: 1.0 Beta */
/***************************************************************************/
/* ADMIN */
define('ADMIN_EMAIL', 'me@domain.com');
define('ADMIN_USER', 'user');
define('ADMIN_PWD', 'pwd');

/* DEFAULTS METAS */
define('META_TITLE','My creations');
define('META_DESCRIPTION', '');
define('META_KEYWORDS', '');
define('META_AUTHOR', '');
define('META_CONTENT_TYPE', 'text/html; charset=UTF-8');

// JS
define('JQUERY', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
define('HTML5SHIM', 'http://html5shim.googlecode.com/svn/trunk/html5.js');

// FONT NAME
define('FONT_NAME', 'font/stylesheet.css'); // PATH FROM RESOURCES ROOT

// DISQUS ACCOUNT
define('DISQUS_SHORTNAME', 'fmnemesis');

// LANGUES
define('TIME_LOCALE', 'fr_FR');
setlocale(LC_TIME, TIME_LOCALE);

/* APP VERSION
	Change it to update CSS an JS
*/
define('APP_VERSION', '0.1');

/* SERVER CACHE */
define('USE_CACHE', 0);

/* THUMBNAILS
	RE-GENERATE THUMBS IN THE BACK-OFFICE IF YOU EDIT THESE SETTINGS
*/

define('THUMB_PATH', NEMESIS_PROCESS_PATH.'uploads/thumbnails');
define('SMALL_WIDTH', 120);
define('SMALL_HEIGHT', 120);

define('MEDIUM_WIDTH', 800);
define('MEDIUM_HEIGHT', 800);

define('LARGE_WIDTH', 1800);
define('LARGE_HEIGHT', 1600);


/* POSTS PER PAGES */
define('POSTS_NUMBER', 2);

/*
PAGES LIST (The order is this one which will appear in the nav menu)
array(
	'PAGE NAME' => 'CONTROLLER FILE NAME (WITHOUT EXTENSION)'
)
THE ERROR404 PAGE DOES NOT HAVE TO BE LISTED
*/

$NEMESIS = Loader::getInstance();
$NEMESIS->pages = array(
	'history' => 'history',
	'links' => 'links',
	'contact' => 'contact',
);

/* DATABASE TYPE */
define('DB_TYPE', 'SQLITE'); //  YOU CAN TRY "MYSQL" IF SQLITE IS NOT INSTALLED ON THE SERVER

/* SQLITE CONFIGURATION */
define('DB_FILEPATH', 'DB/db.txt'); // PATH FROM ROOT APP

/* MYSQL CONFIGURATION */
define('DB_HOST', 'localhost');
define('DB_NAME', 'dbname');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');
