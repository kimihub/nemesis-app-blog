blog -- app for nemesis
=============================

Author
------------
* Nicolas Castelli (castelli.nc@gmail.com)

General
------------

* CHMOD 777 sur /uploads et /DB

* /config.php : contient toute les constantes de l'application pour son fonctionnement

* /controllers : contient les pages (/controllers/mapage.php) � enregistrer dans le fichier /config.php pour qu'elle soit list� dans le "loader" du framework

* /resources : contient toutes les resources (fichiers javascripts, css, images design etc)


Resources
------------

Depuis les controllers les resources peuvent �tre r�cup�r�es avec la variable "$MVC" (d�j� d�clar�) qui est une instance de l'application BLOG

CSS : charge le fichier /apps/blog/resources/css/main.css au bon endroit dans le code HTML, � noter que l'argument attendu par la m�thode loadCSS est le chemin du fichier depuis le r�pertoire "resources" de l'application

	$MVC->loadCSS('css/main.css');

JS

	$MVC->loadJS('js/main.js');


Images : toutes les images du r�pertoire /uploads sont accessibles depuis ce lien

	/apps/blog/uploads/monimage.jpg

	/images/full/monimage.jpg

	/images/full/{imageID}

	 
Miniatures

	<!-- ORIGINAL -->
	/images/full/monimage.jpg

	<!-- SMALL -->
	/images/small/monimage.jpg

	<!-- MEDIUM -->
	/images/medium/monimage.jpg

	<!-- LARGE -->
	/images/large/monimage.jpg
	
	
Ins�rer une image

	<img src="<?php echo new URL('images/full/monimage.jpg') ?>" />

Ins�rer des fichiers du r�pertoire public

	<a href="/public/CV.pdf">Mon CV</a>
	
ou si le framework n'est pas � la racine du nom de domaine

	<a href="<?php echo NEMESIS_URL ?>public/CV.pdf">Mon CV</a>


Fichier functions.php
------------
Est inclu dans tous les fichiers de l'application, permet aussi de modifier les fontions de base du framework dans /core/functions.php


Fonctionnement du router (documentation framework nemesis)
------------
D�tecter si la requ�te est ajax (Ent�tes jQuery/Mootools...)

	if(URL::isHttpRequest()) {
		// code � executer
		die;
	}


$HASH contient l'URL d�coup�

exemple :
	
	http://www.monsite.com/contact/object/reclamation


depuis le controller contact.php :
	
	echo $HASH; 
	// array('object, 'reclamation')


Libraries Dependencies
------------
* RedBeanPHP 3

Plugins Dependencies
------------
* HTMLhelpers
* Images
* CSSmin

Resources Dependencies
------------
* jQuery 1.9
* jQuery html5lightbox
* jQuery html5 filedrop
* jQuery infinite scroll helper
* jQuery lazyload
* jQuery sortable
* jQuery timers
* jQuery tagInput
* jQuery wyziwym
* jQuery autogrow
* jQuery showdown (markdown to html)
* jQuery toMarkown (html to markdown)
* jQuery customs plugins
* html5shiv
* Disqus comments API
* CSS Normalize/Responsive
* CSS customs forms (transitions and CSS3 properties)
* alegreya-regular-webfont generated from fontSquirrel


Changelog
------------

### 0.2
* Bugs upload image fixed when posting
* Adapted to lighttpd config

### 0.1
* Initial Release
