<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
	<style>
	@media print{
		@page {size: portrait; margin: 40px 20px 40px 20px; }
	}
	</style>
	<table class="table table-sm">
		<colgroup>
			<col />
			<col style="width: 10em;" />
			<col style="width: 10em;" />

		</colgroup>

		<thead class="small">
			<tr>
				<td>description</td>
				<td class="text-center">last</td>
				<td class="text-center">current</td>
			</tr>

		</thead>

		<tbody>
			<?php	$tot = 0;
			$stot = 0;
			$type = '';
			foreach( $this->data->dtoSet as $dto) {
				if ( $type != $dto->gl_type) {
					if ( $type != '') {
						printf( '<tr><td colspan="2">&nbsp;</td><td class="text-right">%s</td></tr>', number_format( $stot, 2));
						$stot = 0;

					}
					$type = $dto->gl_type;
					printf( '<tr><td colspan="3" class="bold">%s</td></tr>', trading::type( $type));

				}
				$stot -= (float)$dto->glt_value;
				$tot -= (float)$dto->glt_value;

				?>
				<tr>
					<td><?= $dto->gl_description ?></td>
					<td></td>
					<td class="text-right"><?= number_format( -(float)$dto->glt_value, 2) ?></td>

				</tr>

			<?php	}

			if ( $type != '') {
				printf( '<tr><td colspan="2">&nbsp;</td><td class="text-right">%s</td></tr>', number_format( $stot, 2));
				$stot = 0;

			}	?>

		</tbody>

		<tfoot>
			<tr>
				<td>&nbsp;</td>
				<td class="text-right"><?= sprintf( 'trading %s', ( $tot < 0 ? 'deficit' : 'surplus' )) ?></td>
				<td class="text-right"><?= number_format( $tot, 2) ?></td>

			</tr>

		</tfoot>

	</table>
