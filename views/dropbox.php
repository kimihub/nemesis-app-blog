<?php $MEDIAS = $COLLECTIONS->medias; ?>
<?php $HTML = $COLLECTIONS->HTML; ?>

<div class="commands">
	<p class="submit">
		<?php echo $HTML->button(array('id' => 'deleteSelected', 'value' => 'Delete selected items')) ?>
	</p>
</div>

<div id="dropbox" class="deleteItems">

</div>

<div class="medias">
<?php ob_start(); ?>
<?php foreach ($MEDIAS as $m) : ?>
<?php echo $HTML->hidden(array('name' => '', 'id' => 'media_'.$m->id, 'value' => $m->id)) ?>
<?php endforeach; ?>
<?php echo $HTML->form(ob_get_clean(), array(), array('enctype'=> 1)) ?>
</div>
