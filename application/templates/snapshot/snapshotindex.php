<?php

$this->addStyle(Conf::get('general.url.css').'/snapshot.css');
$this->addScript(Conf::get('general.url.js').'/tabbing.js');
$this->addScript(Conf::get('general.url.js').'/snapshot.js');

?>
	<ul id="actions">
		<li><a href="<?php echo Conf::get('general.url.www'); ?>/database/edit/">Database toevoegen</a></li>
	</ul>
	<div id="tabholder">
		<ul id="tabmenu">
		<?php $i = 0; foreach ($records as $record) : ?>
			<?php if ($i == 0) : $i++; ?>
			<li class="active"><a href="#" class="<?php echo $record->getName(); ?>"><?php echo $record->getName(); ?></a></li>
			<?php else: ?>
			<li><a href="#" class="<?php echo $record->getName(); ?>"><?php echo $record->getName(); ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
		<div id="action">
			<a href="#" class="prev">&lt;</a>
			<a href="#" class="next">&gt;</a>
		</div>
	</div>

	<?php if (!empty($error)) : ?>
		<div style="background-color: red; color: #fff; font-weight: bold;padding: 10px 5px;"><?php echo $error; ?></div>
	<?php endif; ?>
		<div id="snapshots">
	<?php foreach ($records as $record) : ?>
			<fieldset class="tab" id="<?php echo $record->getName(); ?>tab">
				<div style="margin: 0 0 20px 0;">
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/snapshot/create/<?php echo $record->getID() ?>">Create snapshot</a> of: <strong><?php echo $record->getName(); ?></strong>
				</div>
				<?php if (isset($snapshots[$record->getName()]) && count($snapshots[$record->getName()]) > 0) : ?>
				<table class="snaps">
					<tr>
						<th>Name</th>
						<th>Time</th>
						<th>actions</th>
						<th>rename</th>
						<th>Download</th>
					</tr>
		<?php foreach ($snapshots[$record->getName()] as $snapshot) : ?>
				<tr>
					<td>
			<?php $label = $snapshot->getLabel(); ?>
			<?php if (!empty($label)) : ?>
					<strong><?php echo $label; ?></strong>
			<?php else : ?>
							No name
			<?php endif; ?>
					</td>
					<td>
			<?php echo date('d-m-Y H:i:s', $snapshot->getTimeOfCreation()); ?>
					</td>
					<td>
						<div>
							<a href="<?php echo Conf::get('general.url.www'); ?>/snapshot/restore/<?php echo $record->getID(); ?>/<?php echo $snapshot->getFile()->getFilename(); ?>" title="Restore" class="button">Restore</a>
							<a href="<?php echo Conf::get('general.url.www'); ?>/snapshot/delete/<?php echo $record->getID(); ?>/<?php echo $snapshot->getFile()->getFilename(); ?>" title="Delete" class="button">Delete</a>
						</div>
					</td>
					<td>
						<form style='height:12px;' action="<?php echo Conf::get('general.url.www'); ?>/snapshot/rename/<?php echo $record->getID(); ?>/<?php echo $snapshot->getFile()->getFilename(); ?>" method='post'>
							<input type="text" value="" name="renameto" size="16" /><input type="submit" value="rename" class="button" />
						</form>
					</td>
					<td>
						<a href="<?php echo Conf::get('general.url.www'); ?>/snapshot/download/<?php echo $record->getID(); ?>/<?php echo $snapshot->getFile()->getFilename(); ?>">Download snapshot</a>
					</td>
				</tr>
		<?php endforeach; ?>
				</table>
				<?php else: ?>
				<div>No snapshots yet</div>
				<?php endif; ?>
			</fieldset>
		<?php endforeach; ?>
		</div>
	</body>
</html>