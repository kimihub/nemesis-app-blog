<?php $HTML = $MVC->NEMESIS->plugin('HTMLhelpers') ?>
<div class="login">
	<p><?php echo $HTML->text(array('id' => 'user', 'placeholder' => 'Nom d\'utilisateur')) ?></p>
	<p><?php echo $HTML->password(array('id' => 'pwd', 'placeholder' => 'Mot de passe')) ?></p>
	<p><?php echo $HTML->button(array('id' => 'connect', 'value' => 'Connexion')) ?></p>
</div>