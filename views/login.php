<?php $HTML = new HTMLhelpers(); ?>
<div class="login">
	<p><?php echo $HTML->text(array('id' => 'user', 'placeholder' => 'Username')) ?></p>
	<p><?php echo $HTML->password(array('id' => 'pwd', 'placeholder' => 'Password')) ?></p>
	<p><?php echo $HTML->button(array('id' => 'connect', 'value' => 'Login')) ?></p>
</div>
