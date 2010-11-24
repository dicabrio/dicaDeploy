	<ul id="actions">
		<li><a href="<?php echo Conf::get('general.url.www'); ?>/repository/edit/">Repository toevoegen</a></li>
	</ul>

	<table class="dataset">
		<thead>
			<tr>
				<td>#</td>
				<td>Reponaam</td>
				<td>Laatste update</td>
				<td>Gevalideerd</td>
				<td>Acties</td>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($repos as $repo): ?>
			<tr>
				<td><?php echo $repo->getID(); ?></td>
				<td><?php echo $repo->getName(); ?></td>
				<td><?php echo date('d m Y', $repo->getLastupdate()); ?></td>
				<td>
					<?php if ($repo->isValidated()) : ?>
					ja
					<?php else: ?>
					nee
					<?php endif; ?>
				</td>
				<td>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/repository/edit/<?php echo $repo->getID(); ?>">Wijzig</a>
					<a class="button deletepage" confirm="weet je het zeker dat je deze repository wilt verwijderen?" href="<?php echo Conf::get('general.url.www'); ?>/repository/delete/<?php echo $repo->getID(); ?>">Verwijder</a>
					<?php if (!$repo->isValidated()) : ?>
					<a class="button" href="<?php echo Conf::get('general.url.www'); ?>/repository/validate/<?php echo $repo->getID(); ?>">Valideer</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach ?>

		</tbody>
	</table>