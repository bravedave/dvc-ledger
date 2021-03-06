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
		<thead class="small">
			<tr>
				<td>description</td>
				<td style="width: 10em;" class="text-right">open</td>
				<td style="width: 10em;" class="text-right">current</td>
			</tr>

		</thead>

		<tbody>
			<?php
			$tot = 0;
			$stot = 0;
			$type = '';
			foreach( $this->data->dtoSet as $dto) {
				if ( $type != $dto->gl_type) {
					if ( $type != '') {
						printf( '<tr><td colspan="2">&nbsp;</td><td class="text-right">%s</td></tr>', number_format( $stot, 2));
						$stot = 0;

					}
					$type = $dto->gl_type;
					printf( '<tr><td colspan="3" class="bold">%s</td></tr>', balsheet::type( $type));

				}
				$stot += $dto->glt_value;
				$tot += $dto->glt_value;

				?>
				<tr>
					<td><?php print $dto->gl_description ?></td>
					<td></td>
					<td class="text-right"><?= number_format( $dto->glt_value, 2) ?></td>

				</tr>
				<?php
			}

			if ( $type != '') {
				printf( '<tr><td colspan="2">&nbsp;</td><td class="text-right">%s</td></tr>', number_format( $stot, 2));
				$stot = 0;

			}	?>

		</tbody>

	</table>
