<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-striped table-sm">
	<colgroup>
		<col style="width: 5em" />
		<col style="width: 7em" />
		<col />
		<col style="width: 7em" />
		<col style="width: 7em" />
	</colgroup>

	<thead>
		<tr>
			<td>code</td>
			<td>date</td>
			<td>description</td>
			<td class="text-center">debit</td>
			<td class="text-center">credit</td>
		</tr>

	</thead>

	<tbody>
<?php	$tot = 0;
		$debit = 0;
		$credit = 0;
		foreach ( $this->data->dtoSet as $dto) {
			$tot += $dto->glt_value;
			if (  $dto->glt_value > 0)
				$debit += $dto->glt_value;
			else
				$credit += $dto->glt_value;
			?>
		<tr>
			<td><?php print $dto->glt_code	?></td>
			<td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->glt_date))	?></td>
			<td><?php print $dto->glt_comment	?></td>
			<td class="text-right"><?php print ( $dto->glt_value > 0 ? number_format( $dto->glt_value, 2) : '&nbsp;' ) ?></td>
			<td class="text-right"><?php print ( $dto->glt_value <= 0 ? number_format( $dto->glt_value, 2) : '&nbsp;' ) ?></td>

		</tr>

<?php	}	// foreach ( $this->data->dtoSet as $dto)	?>

	</tbody>

	<tfoot>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right"><?php print number_format( $debit, 2) ?></td>
			<td class="text-right"><?php print number_format( $credit, 2) ?></td>

		</tr>

	</tfoot>


</table>
