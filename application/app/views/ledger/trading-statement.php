<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<table class="table table-striped table-sm">
	<colgroup>
		<col />
		<col style="width: 10em;" />
		<col style="width: 10em;" />

	</colgroup>

	<thead>
		<tr>
			<td>description</td>
			<td>open</td>
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
			$stot += $dto->glt_value;
			$tot += $dto->glt_value;

		?>
		<tr>
			<td><?php print $dto->gl_description ?></td>
			<td></td>
			<td class="text-right"><?php print number_format( $dto->glt_value, 2) ?></td>

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
			<td>&nbsp;</td>
			<td class="text-right"><?php print number_format( $tot, 2) ?></td>

		</tr>

	</tfoot>

</table>
