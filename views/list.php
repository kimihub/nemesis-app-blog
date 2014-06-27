<?php $POSTS = $COLLECTIONS->posts ; ?>
<?php $SINGLE = (isset($COLLECTIONS->single)? 1:0) ; ?>
<?php $ADMIN = (isset($COLLECTIONS->admin)? $COLLECTIONS->admin:0) ; ?>
<?php foreach ($POSTS as $post) : ?>
<article<?php echo ($post->type == 'draft')? ' class="draft"':'' ?>>
	<header>
		<h1><?php echo $post->title ?></h1>
		<?php if ($post->type == 'draft') : ?>
		<span>Brouillon</span>
		<?php endif; ?>
		<datetime><?php echo date('d/m/Y', $post->publishDate); ?></datetime>
		<?php $cats = category::getAttachmentsFrom($post) ?>
		<?php if (!empty($cats)) : ?>
		<ul class="categories">
		<?php foreach ($cats as $c) : ?>
		<li><a href="<?php echo new URL('cat/'.$c->id) ?>"><?php echo $c->name ?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</header>
	<section>
		<?php echo $post->caption ?>
	</section>
	<?php $medias = media::getAttachmentsFrom($post); ?>
	<section class="<?php echo (count($medias) > 0)? 'medias':'' ?><?php echo (($post->mediaType == 'gallery')? ' gallery':' thumbnails') ?>">
		<ul class="notLoaded">
			<?php if ($post->mediaType == 'gallery' && sizeof($medias) > 1) : ?>
			<li class="controlLeft"><span><</span></li>
			<?php endif; ?>
			<?php $i = 0 ?>
			<?php foreach ($medias as $m) : ?>
			<li<?php echo (($post->mediaType == 'gallery' && $i == 0)? ' class="current"':'') ?><?php echo ((sizeof($medias) == 1)? ' id="mediaFull"':'') ?>><a data-width="<?php echo LARGE_WIDTH ?>" data-height="<?php echo LARGE_HEIGHT ?>" data-group="article-<?php echo $post->id ?>" href="<?php echo new URL('images/large/'.$m->name) ?>" target="_blank"><img src="<?php echo new URL('images/'.(($post->mediaType == 'thumbnails')? 'small':'medium'). '/'.$m->name) ?>" alt="<?php echo $m->name ?>" /></a></li>
			<?php $i++ ?>
			<?php endforeach; ?>
			<?php if ($post->mediaType == 'gallery' && sizeof($medias) > 1) : ?>
			<li class="controlRight"><span>></span></li>
			<?php endif; ?>
		</ul>
		<div class="clr"></div>
	</section>
	<footer>
		<ul>
		<?php if (!$SINGLE) : ?>
			<li class="comment"><a class="button" href="<?php echo new URL('post/'.$post->id) ?>">Commenter</a></li>
		<?php endif; ?>
		<?php if ($ADMIN) : ?>
			<li><a class="button edit" href="<?php echo new URL('post/add/'.$post->id) ?>">Editer</a><li>
			<li><a class="button delete" data-id="<?php echo $post->id ?>" href="#">Supprimer</a><li>
		<?php endif; ?>
		</ul>
		<div class="clr"></div>
		<?php if ($SINGLE) : ?>
		<?php $MVC->render('disqus_load') ?>
		<?php $MVC->render('disqus_comments') ?>
		<?php endif; ?>
		
	</footer>
</article>
<?php endforeach; ?>
