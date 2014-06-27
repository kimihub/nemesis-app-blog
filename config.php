<?php
/***************************************************************************/
/* Author: Nicolas Castelli */
/* App Version: 1.0 Beta */
/* Vous pouvez modifier les constantes ci-dessous pour changer le comportement générale de l'application mais */
/* attention à ne pas les supprimer, certaines sont indispensables au fonctionnement de l'application ! */
/* si vous n'avez pas besoin d'une des constantes, définissez une valeur vide */
/***************************************************************************/
/* ADMIN */
define('ADMIN_EMAIL', 'castelli.nc@gmail.com');
define('ADMIN_USER', 'user');
define('ADMIN_PWD', 'pwd');

/* DEFAULTS METAS */
define('META_TITLE','Mes créations');
define('META_DESCRIPTION', '');
define('META_KEYWORDS', '');
define('META_AUTHOR', '');
define('META_CONTENT_TYPE', 'text/html; charset=UTF-8');

// JS
define('JQUERY', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
define('HTML5SHIM', 'http://html5shim.googlecode.com/svn/trunk/html5.js');

// FONT NAME
define('FONT_NAME', 'font/stylesheet.css'); // CHEMIN DEPUIS LA RACINE DES RESOURCES

// DISQUS ACCOUNT
define('DISQUS_SHORTNAME', 'fmnemesis');

// LANGUES
define('TIME_LOCALE', 'fr_FR');
setlocale(LC_TIME, TIME_LOCALE);

/* APP VERSION  
	Changer cette valeur pour mettre à jour le cache à la fois côté serveur et côté client en cas de mise à jour CSS ou JS !
*/
define('APP_VERSION', '0.1');

/* SERVER CACHE */
define('USE_CACHE', 0);

/* THUMBNAILS 
	PENSEZ A RE-CREER LES MINIATURES DANS LE BACK-OFFICE SI VOUS MODIFIEZ CES PARAMETRES
*/
define('SMALL_WIDTH', 120);
define('SMALL_HEIGHT', 120);

define('MEDIUM_WIDTH', 800);
define('MEDIUM_HEIGHT', 800);

define('LARGE_WIDTH', 1800);
define('LARGE_HEIGHT', 1600);


/* ARTICLES PAR PAGES */
define('POSTS_NUMBER', 2);

/* 
LISTE DES PAGES (l'ordre est celui qui apparaitra dans le menu des pages
array(
	'NOM DE MA PAGE' => 'NOM DU FICHIER DU CONTROLLER (SANS l'EXTENSION)'
)
SEULE LA PAGE ERROR404 N'EST PAS A LISTER
*/

$NEMESIS = Loader::getInstance();
$NEMESIS->pages = array(
	'chronologie' => 'chronologie',
	'liens' => 'liens',
	'contact' => 'contact',
);

/* TYPE DE BASE DE DONNEE */
define('DB_TYPE', 'SQLITE'); //  VOUS POUVEZ ESSAYER "MYSQL" SI SQLITE N'EST PAS INSTALLEE SUR VOTRE SERVEUR

/* CONFIGURATION POUR SQLITE */
define('DB_FILEPATH', 'DB/db.txt'); // CHEMIN DEPUIS LA RACINE DE L'APPLICATION

/* CONFIGURATION POUR MYSQL */
define('DB_HOST', 'localhost');
define('DB_NAME', 'dbname');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');

