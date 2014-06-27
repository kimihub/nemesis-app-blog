<?php $HTML = $COLLECTIONS->HTML; ?>
<?php $imagesURL = $COLLECTIONS->imagesURL; ?>
<?php $POST = $COLLECTIONS->post; ?>
<?php
	$CATS = array();
	foreach ($COLLECTIONS->cats as $cat)
	{
		$CATS[] = $cat->name;
	}
	
	$CATS_JSON = json_encode($CATS);
	$CATS = implode (',', $CATS);
?>
<?php $MEDIAS = $COLLECTIONS->medias; ?>

<?php
	$TAGS = array();
	foreach ($COLLECTIONS->tags as $tag)
	{
		$TAGS[] = $tag->name;
	}
	
	$TAGS_JSON = json_encode($TAGS);
	$TAGS = implode(',', $TAGS);
?>

<?php ob_start(); ?>

<?php echo $HTML->hidden(array('name' => 'id', 'value' => $POST->id)) ?>

<div class="commands">
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aTitle', 'value' => 'Titre')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aPublishDate', 'value' => 'Date')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aTags', 'value' => 'Mots-clefs')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aCats', 'value' => 'Thèmatiques')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aCaption', 'value' => 'Légende')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aMediaType', 'value' => 'Affichage')) ?>
	</p>
	<p class="shortcut">
		<?php echo $HTML->button(array('data-id' => 'aDropbox', 'value' => 'Images')) ?>
	</p>
	<p class="submit">
	<?php echo (!$POST->type || $POST->type == 'draft')? $HTML->submit(array('name' => 'draft', 'value' => ($POST->type == 'draft')? 'Enregistrer le brouillon':'Enregistrer un brouillon')):'' ?>
	<?php echo $HTML->submit(array('name' => 'save', 'value' => 'Poster')) ?>
	</p>
</div>

<h3 id="aTitle">Titre</h3>
<p><?php echo $HTML->text(array('name' => 'title', 'value' => $POST->title)) ?></p>

<h3 id="aPublishDate">Date de publication</h3>
<p><?php echo $HTML->input('date', array('name' => 'publishDate', 'value' => (is_date($POST->publishDate) == 'TIMESTAMP')? date('Y-m-d',$POST->publishDate):date('Y-m-d'), 'class' => 'date')) ?></p>


<h3 id="aTags">Mots-clefs</h3>
<p><?php echo $HTML->text(array('id' => 'tags', 'name' => 'tags', 'value' => $TAGS)) ?><span class="suggestions"><strong>Suggestions</strong> : <span id="suggestedTags" class="tagInputSuggestedTagList"></span></span></p>

<script type="text/javascript">
    // $(function() {
		// $("#tags").tagInput({
		  // tags: <?php echo $TAGS_JSON ?>,
		  // jsonUrl:"tags.jsp",
		  // sortBy:"frequency",
		  // suggestedTags:{$tags_suggestions},
		  // tagSeparator:",",
		  // autoFilter:true,
		  // autoStart:true,
		  // suggestedTagsPlaceHolder:$("#suggestedTags"),
		  // boldify:true

		// });
    // });
</script>

<h3 id="aCats">Thèmatiques</h3>
<p><?php echo $HTML->text(array('id' => 'cats', 'name' => 'cats', 'value' => $CATS)) ?><span class="suggestions"><strong>Suggestions</strong> : <span id="suggestedCats" class="tagInputSuggestedTagList"></span></span></p>

<script type="text/javascript">
    // $(function() {
		// $("#cats").tagInput({
		  // tags: <?php echo $CATS_JSON ?>,
		  // jsonUrl:"tags.jsp",
		  // sortBy:"frequency",
		  // suggestedTags:{$cats_suggestions},
		  // tagSeparator:",",
		  // autoFilter:true,
		  // autoStart:true,
		  // suggestedTagsPlaceHolder:$("#suggestedCats"),
		  // boldify:true

		// });
		
	// });
</script>


<h3 id="aCaption">Légende</h3>
<p><?php echo $HTML->textarea(array('id' => 'caption', 'name' => 'caption', 'value' => $POST->caption)) ?></p>
<div style='clear:both;'></div>
<div id="caption-preview"></div>


<h3 id="aMediaType">Type d'affichage des images</h3>
<p>
<?php if ($POST->mediaType == 'thumbnails') : ?>
<?php echo $HTML->radio(array('name' => 'mediaType', 'value' => 'thumbnails', 'id' => 'thumbnails', 'checked' => 'checked')) ?>
<?php else : ?>
<?php echo $HTML->radio(array('name' => 'mediaType', 'value' => 'thumbnails', 'id' => 'thumbnails')) ?>
<?php endif; ?>
<?php echo $HTML->label(array('value' => 'Miniatures', 'for' => 'thumbnails')) ?>
</p>
<p>
<?php if ($POST->mediaType == 'gallery') : ?>
<?php echo $HTML->radio(array('name' => 'mediaType', 'value' => 'gallery', 'id' => 'gallery', 'checked' => 'checked')) ?>
<?php else : ?>
<?php echo $HTML->radio(array('name' => 'mediaType', 'value' => 'gallery', 'id' => 'gallery')) ?>
<?php endif; ?>
<?php echo $HTML->label(array('value' => 'Galerie', 'for' => 'gallery')) ?>
</p>

<h3 id="aDropbox">Images</h3>
<p><?php echo $HTML->text(array('placeholder' => 'Ajouter une image à partir d\'un lien http://...', 'class' =>'addLink')) ?></p>
<div id="dropbox">
	<span class="message"><i>Déplacer vos images ici ...</i></span>
</div>
<div class="medias">
<?php foreach ($MEDIAS as $m) : ?>
<?php echo $HTML->hidden(array('name' => ( R::areRelated($POST, $m))? 'medias[]':'', 'id' => 'media_'.$m->id, 'value' => $m->id)); ?>
<?php endforeach; ?>
</div>

<?php echo $HTML->form(ob_get_clean(), array(), array('enctype'=> 1));?>