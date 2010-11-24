<?php $this->addScript(Conf::get('general.url.js').'/server.js'); ?>
<?php $this->addStyle(Conf::get('general.url.css').'/server.css'); ?>
<ul id="tabmenu">
	<li class="active"><a href="#">Edit Server</a></li>
</ul>
<?php echo $form->begin(); ?>
<fieldset class="tab">
	<?php if (count($errors) > 0) : ?>
	<ul class="error">
			<?php foreach ($errors as $sError) : ?>
		<li><?php echo Lang::get('server.'.$sError); ?></li>
			<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<div class="pagemodule">
		<div class="modulelabel">Name:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('name'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Hostname:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('hostname')->addAttribute('id', 'hostname'); ?>&nbsp;
			<span class="default hostname">webgamic1.dedicated.nines.nl</span>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">SSH gebruiker:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('user'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">SSH wachtwoord:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('password'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Repositorpad:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('repopath')->addAttribute('id', 'repopath'); ?>&nbsp;
			<span class="default repopath">/home/username/</span>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Repository:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('repo_id'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="pagemodule">
		<div class="modulelabel">Repository branch:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('repobranch'); ?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
</fieldset>
<fieldset class="actions">
	<div class="pagemodule">
		<div class="modulelabel">Actions:</div>
		<div class="modulecontent">
			<?php echo $form->getFormElement('save')->addAttribute('class', 'button'); ?>
			<a href="<?php echo Conf::get('general.url.www').'/server'; ?>" class="button">Cancel</a>
		</div>
	</div>
</fieldset>
<?php echo $form->end(); ?>