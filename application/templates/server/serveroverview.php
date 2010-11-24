	<ul id="actions">
		<li><a href="<?php echo Conf::get('general.url.www'); ?>/server/edit/">Repository toevoegen</a></li>
	</ul>

	<?php if (count($errors) > 0) : ?>
	<ul class="error">
			<?php foreach ($errors as $sError) : ?>
		<li><?php echo Lang::get('server.'.$sError); ?></li>
			<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<table class="dataset">
		<thead>
			<tr>
				<td>#</td>
				<td>Server</td>
				<td>Hostname</td>
				<td>Gekoppelde Repository</td>
				<td>Repository branch</td>
				<td>Gevalideerd</td>
				<td>Acties</td>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($servers as $servers): ?>
			<tr>
				<td><?php echo $servers->getID(); ?></td>
				<td><?php echo $servers->getName(); ?></td>
				<td><?php echo $servers->getHostname(); ?></td>
				<td><?php echo $servers->getRepo()->getName(); ?></td>
				<td><?php echo $servers->getRepobranch(); ?></td>
				<td>
					<?php if ($servers->isValidated()) : ?>
					ja
					<?php else: ?>
					nee
					<?php endif; ?>
				</td>
				<td>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/server/edit/<?php echo $servers->getID(); ?>">Wijzig</a>
					<a class="button deletepage" confirm="weet je het zeker dat je deze repository wilt verwijderen?" href="<?php echo Conf::get('general.url.www'); ?>/server/delete/<?php echo $servers->getID(); ?>">Verwijder</a>
					<?php if (!$servers->isValidated()) : ?>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/server/validate/<?php echo $servers->getID(); ?>">Valideer</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach ?>

		</tbody>
	</table>