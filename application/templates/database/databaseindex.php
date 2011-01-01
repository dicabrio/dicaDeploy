	<ul id="actions">
		<li><a href="<?php echo Conf::get('general.url.www'); ?>/database/edit/">Database toevoegen</a></li>
	</ul>

	<table class="dataset">
		<thead>
			<tr>
				<td>#</td>
				<td>Databasename</td>
				<td>Host</td>
				<td>Type</td>
				<td>Gevalideerd</td>
				<td>Acties</td>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($records as $record): ?>
			<tr>
				<td><?php echo $record->getID(); ?></td>
				<td><?php echo $record->getName(); ?></td>
				<td><?php echo $record->getHost(); ?></td>
				<td><?php echo $record->getType(); ?></td>
				<td>
					<?php if ($record->isValidated()) : ?>
					ja
					<?php else: ?>
					nee
					<?php endif; ?>
				</td>
				<td>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/database/edit/<?php echo $record->getID(); ?>">Wijzig</a>
					<a class="button deletepage" confirm="weet je het zeker dat je deze database wilt verwijderen?" href="<?php echo Conf::get('general.url.www'); ?>/database/delete/<?php echo $record->getID(); ?>">Verwijder</a>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/snapshot/#<?php echo $record->getName(); ?>">Bekijk snapshots</a>
					<!--<?php if (!$record->isValidated()) : ?>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/database/validate/<?php echo $record->getID(); ?>">Valideer</a>
					<?php endif; ?>-->
				</td>
			</tr>
			<?php endforeach ?>

		</tbody>
	</table>