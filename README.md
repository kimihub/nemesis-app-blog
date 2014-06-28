BLOG APPLICATION 
=============================

Author
------------
* Nicolas Castelli (castelli.nc@gmail.com)

Général
------------

* CHMOD 777 sur /uploads et /DB

* /config.php : contient toute les constantes de l'application pour son fonctionnement

* /controllers : pages sont à insérer dans /controllers (/controllers/mapage.php) puis l'enregistrer dans le fichier /config.php pour qu'elle soit listé dans le "loader" du framework

* /resources : toutes les resources (fichiers javascripts, css, images design etc) sont à placer dans /resources


Resources
------------

Depuis les controllers les resources peuvent être récupérées avec la variable "$MVC" (déjà déclaré) qui est une instance de l'application BLOG

* CSS : charge le fichier /apps/blog/resources/css/main.css au bon endroit dans le code HTML, à noter que l'argument attendu par la méthode loadCSS est le chemin du fichier depuis le répertoire "resources" de l'application
	$MVC->loadCSS('css/main.css');

* JS
	$MVC->loadJS('js/main.js');

* Images : toutes les images du répertoire /uploads sont accessibles depuis ce lien
	<img src="/apps/blog/uploads/monimage.jpg" />
ou
	<img src="/images/full/monimage.jpg" />
ou
	<img src="/images/full/{imageID}" />
	 
* Miniatures
ORININAL 
	<img src="/images/full/monimage.jpg" />
SMALL
	<img src="/images/small/monimage.jpg" />
MEDIUM 
	<img src="/images/medium/monimage.jpg" />
LARGE
	<img src="/images/large/monimage.jpg" />
	
* Insérer une image
	<img src="<?php echo new URL('images/full/monimage.jpg') ?>" />

* Insérer des fichiers du répertoire public
	<a href="/public/CV.pdf">Mon CV</a>
ou si le framework n'est pas à la racine du nom de domaine
	<a href="<?php echo NEMESIS_URL ?>public/CV.pdf">Mon CV</a>


functions.php
------------
Est inclu dans tous les fichiers de l'application, permet aussi de modifier les fontions de base du framework dans /core/functions.php


Fonctionnement du router (documentation framework nemesis)
------------
* Détecter si la requête est ajax (Entêtes jQuery/Mootools...)
	if(URL::isHttpRequest()) {
		// code à executre
		die;
	}

* $HASH contient l'URL découpé
exemple
	http://www.monsite.com/contact/object/reclamation
depuis le controller contact.php
	echo $HASH; 
	// array('object, 'reclamation')


Libraries Dependencies
------------
* RedBeanPHP 3

Plugins Dependencies
------------
* HTMLhelpers
* Images
*	CSSmin

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
