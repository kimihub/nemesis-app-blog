<h1>{$categories_edit_title}</h1>
<div>
<ul class="sortable list noPadding">
<?php $HTML = $COLLECTIONS->HTML; ?>
<?php foreach ($COLLECTIONS->categories as $cat) : ?>
	<li data-id="<?php echo $cat->id ?>"><?php echo $HTML->button(array('class' => 'deleteCat', 'value' => '-')) ?><?php echo $cat->name ?></li>
<?php endforeach; ?>
</ul>
</div>
<p><?php echo $HTML->text(array('class' => 'nameCategory', 'value' => '', 'placeholder' => 'Add a category')) ?>
<?php echo $HTML->button(array('class' => 'addCategory noPadding', 'value' => '+')) ?></p>
<p><?php echo $HTML->button(array('class' => 'position', 'name' => 'position', 'value' => 'Save categories order')) ?></p>
