# Nemesis App Example - Blog #

Here is an example of a MVC Web App built on [Nemesis Framework](https://github.com/kimihub/nemesis-framework).

This app is a lightweight [jQuery](https://www.jquery.com) blog where you can manage posts, categories, images, display them as a gallery, thumbnails, or a lightbox...
There is not any pagination, the posts page displays the items with a lazy load system.

To test it, simply deploy it on a PaaS like [Heroku](https://www.heroku.com/) or [Openshift](https://www.openshift.com/).

Keep in mind that is just a demo or a skeleton for a Nemesis App, its stability in production has not been tested.


Demo
------------

Link : [http://demo-nemesis-app.herokuapp.com/](http://demo-nemesis-app.herokuapp.com/)

Username : user

Password : pwd


Configuration
------------

* /config.php : contains all constants of the app

* /controllers : contains all pages (/controllers/mypage.php) to define in /config.php and list in the loader framework

* /resources : contains all resources (javascripts, css, images design etc), **this is also the public directory**


Resources
------------

From controllers all ressources can be included with "$MVC" which is an instance of the app

CSS : load the file /apps/blog/resources/css/main.css

	$MVC->loadCSS('css/main.css');

JS

	$MVC->loadJS('js/main.js');


Images : all images in /uploads are reachable from these links

	/images/full/image.jpg

	/images/full/{imageID}


Thumbnails

	<!-- ORIGINAL -->
	/images/full/image.jpg

	<!-- SMALL -->
	/images/small/image.jpg

	<!-- MEDIUM -->
	/images/medium/image.jpg

	<!-- LARGE -->
	/images/large/image.jpg


Insert an image

	<img src="<?php echo new URL('images/full/image.jpg') ?>" />

Insert a file in resources public directory

	<a href="/file.pdf">My file</a>

or

	<a href="<?php echo NEMESIS_URL ?>/file.pdf">My </a>



Router
------------
Check [Nemesis-Framework README](https://github.com/kimihub/nemesis-framework)

    //jQuery/Mootools headers
	if(URL::isHttpRequest()) {
		// code
		die;
	}


$HASH contains the split URL

example :

	http://www.website.com/contact/object/reclamation


from the contact.php controller :

	echo $HASH;
	// array('object, 'reclamation')


PHP ext dependencies
------------
* pdo_sqlite
* gd
* exif
* curl

PHP lib dependencies
------------
* [RedBeanPHP 3.7](http://www.redbeanphp.com/)
* ImageGD

JS, CSS, fonts lib dependencies
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

### 0.4
* load ReadBeanPHP and Nemesis Framework with composer

### 0.3
* implements new features of Nemesis Framework (Session, Old plugins to core)

### 0.2
* Bugs upload image fixed when posting
* Adapted to lighttpd config

### 0.1
* Initial Release
