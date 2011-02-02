<ul id="tabmenu">
	<li class="active"><a href="#">Edit Repository</a></li>
</ul>
<?php echo $form->begin(); ?>
<fieldset class="tab">
	<?php if (count($errors) > 0) : ?>
	<ul class="error">
			<?php foreach ($errors as $sError) : ?>
		<li><?php echo Lang::get('repository.'.$sError); ?></li>
			<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<div class="pagemodule">
		<div class="modulelabel">Type:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('type'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Name:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('name'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Location:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('location'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Username:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('username'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Password:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('password'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
</fieldset>
<fieldset class="actions">
	<div class="pagemodule">
		<div class="modulelabel">Actions:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('action')->addAttribute('class', 'button'); ?>
			<a href="<?php echo Conf::get('general.url.www').'/repository'; ?>" class="button">Cancel</a>
		</div>
	</div>
</fieldset>
<?php echo $form->end(); ?>