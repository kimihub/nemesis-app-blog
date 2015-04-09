<?php if (!URL::isHttpRequest()) : ?><!DOCTYPE HTML>
<html>

<head>
<?php $MVC->render('head') ?>
</head>

<body>

	<div class="header-container">
		<header class="wrapper clearfix">
			<a href="<?php echo new URL('') ?>"><h1 class="title">{$h1_title}</h1></a>
		</header>
	</div>

	<div class="main-container">
		<div class="main wrapper clearfix">
			<aside>
				<h3>{$aside_title}</h3>
				<nav>
					<ul>
						<?php foreach($COLLECTIONS->categories as $cat) : ?>
						<li<?php echo ((isset($COLLECTIONS->category) && $COLLECTIONS->category == $cat->id)? ' class="current"':'') ?>><a href="<?php echo new URL('cat/'.$cat->id) ?>"><?php echo $cat->name ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<?php if (isset($MVC->NEMESIS->pages)) : ?>
				<!-- PAGES LINK BEGIN -->
				<nav class="pages">
					<ul>
						<?php foreach($MVC->NEMESIS->pages as $name => $controller) : ?>
						<li><a href="<?php echo new URL($controller) ?>"><?php echo $name ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<?php endif; ?>
				<!-- PAGES LINK END -->
				
				<?php if (isset($COLLECTIONS->admin)) : ?>
				<h3>admin</h3>
				<nav>
					<ul>
						<li><a href="<?php echo new URL('post/add') ?>">poster</a></li>
						<li><a href="<?php echo new URL('cat/manage') ?>">thèmatiques</a></li>
						<li><a href="<?php echo new URL('dropbox') ?>">images</a></li>
						<li><a href="<?php echo new URL('settings') ?>">maintenance</a></li>
					</ul>
				</nav>
				<input type="button" id="logout" value="Déconnexion" />
				<?php $this->loadJS('js/logout.js') ?>
				<?php else: ?>
				<a href="<?php echo new URL('login') ?>"><h3>administration</h3></a>
				<?php endif; ?>
			</aside>
			<section id="main">
<?php endif; ?>

<?php if (isset($COLLECTIONS->controller)) : ?>
<?php echo $COLLECTIONS->controller ?>
<?php else : ?>
	<?php if (isset($COLLECTIONS->content)) : ?>
	<?php $MVC->render($COLLECTIONS->content) ?>
	<?php endif; ?>
<?php endif; ?>

<?php if (!URL::isHttpRequest()) : ?>
			</section>
		</div> <!-- #main -->
		<div class="loader-wrap">
			<div class="loader"></div>
		</div>
	</div> <!-- #main-container -->

	<div class="footer-container">
		<footer class="wrapper">
		</footer>
	</div>
<?php endif; ?>
	<?php $MVC->render('scripts') ?>
<?php if (!URL::isHttpRequest()) : ?>
</body>

</html>
<?php endif; ?>
