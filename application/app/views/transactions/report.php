<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-sm">
	<colgroup>
		<col style="width: 5em" />
		<col style="width: 7em" />
		<col />
		<col style="width: 7em" />
		<col style="width: 7em" />
		<col style="width: 6em" />
		<col style="width: 6em" />
	</colgroup>

	<thead>
		<tr>
			<td colspan="3">&nbsp;</td>
			<td colspan="2" class="text-center">transaction</td>
			<td colspan="2" class="text-center">gst</td>
		</tr>
		<tr>
			<td>code</td>
			<td>date</td>
			<td>description</td>
			<td class="text-center">debit</td>
			<td class="text-center">credit</td>
			<td class="text-center">debit</td>
			<td class="text-center">credit</td>
		</tr>

	</thead>

	<tbody>
<?php	$tot = 0;
		$debit = 0;
		$credit = 0;
		$totGST = 0;
		$debitGST = 0;
		$creditGST = 0;
		foreach ( $this->data->dtoSet as $dto) {
			$tot += (float)$dto->glt_value;
			$totGST += (float)$dto->glt_gst;
			if ( (float)$dto->glt_value > 0)
				$debit += (float)$dto->glt_value;
			else
				$credit -= (float)$dto->glt_value;
			if ( (float)$dto->glt_gst > 0)
				$debitGST += (float)$dto->glt_gst;
			else
				$creditGST -= (float)$dto->glt_gst;
			?>
		<tr>
			<td><?php print $dto->glt_code	?></td>
			<td><?php print strings::asShortDate( $dto->glt_date)	?></td>
			<td><?php print $dto->glt_comment	?></td>
			<td class="text-right"><?php print ( (float)$dto->glt_value > 0 ? number_format( (float)$dto->glt_value, 2) : '&nbsp;' ) ?></td>
			<td class="text-right"><?php print ( (float)$dto->glt_value < 0 ? number_format( -(float)$dto->glt_value, 2) : '&nbsp;' ) ?></td>
			<td class="text-right"><?php print ( (float)$dto->glt_gst > 0 ? number_format( (float)$dto->glt_gst, 2) : '&nbsp;' ) ?></td>
			<td class="text-right"><?php print ( (float)$dto->glt_gst < 0 ? number_format( -(float)$dto->glt_gst, 2) : '&nbsp;' ) ?></td>

		</tr>

<?php	}	// foreach ( $this->data->dtoSet as $dto)	?>

	</tbody>

	<tfoot>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right">debit/credit</td>
			<td class="text-right"><?php print number_format( (float)$debit, 2) ?></td>
			<td class="text-right"><?php print number_format( (float)$credit, 2) ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right">gst</td>
			<td class="text-right"><?php print number_format( (float)$debitGST, 2) ?></td>
			<td class="text-right"><?php print number_format( (float)$creditGST, 2) ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right">total</td>
			<td class="text-right"><?php print number_format( (float)$debitGST + (float)$debit, 2) ?></td>
			<td class="text-right"><?php print number_format( (float)$creditGST + (float)$credit, 2) ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

		</tr>

	</tfoot>


</table>
