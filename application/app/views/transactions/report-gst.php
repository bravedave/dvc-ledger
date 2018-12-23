<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Outputs gl_type a
	Inputs gl_type b & c

	*/

	//
	?>
<table class="table table-sm">
	<tbody>
		<tr>
			<td colspan="4">Outputs</td>

		</tr>
	<?php
		// report ouputs till inputs come, then rest ..
		$output = true;
		$inputTot = 0;
		$inputGst = 0;
		$outputTot = 0;
		$outputGst = 0;
		$count = 0;
		foreach ( $this->data->dtoSet as $dto) {
			if ( $output && $dto->gl_type !== 'a') {
				$output = false;
				if ( $count) {	?>
		<tr>
			<td class="text-right text-muted" colspan="2">Total Outputs</td>
			<td class="text-right text-muted"><?= number_format( (float)$outputTot, 2) ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$outputGst, 2) ?></td>

		</tr>

		<tr>
			<td colspan="4">Inputs</td>

		</tr>
				<?php
				}

			}

			$factor = 1;
			if ( $output) {
				$factor = -1;
				$outputTot -= $dto->glt_value;
				$outputGst -= $dto->glt_gst;

			}
			else {
				$inputTot += $dto->glt_value;
				$inputGst += $dto->glt_gst;

			}

			$count ++;
			?>
		<tr>
			<td><?= strings::asShortDate( $dto->glt_date); ?></td>
			<td><?= $dto->glt_comment; ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$dto->glt_value*$factor, 2); ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$dto->glt_gst*$factor, 2); ?></td>

		</tr>

	<?php
		}

		if ( $count) {	?>
		<tr>
			<td class="text-right text-muted" colspan="2">Total Inputs</td>
			<td class="text-right text-muted"><?= number_format( (float)$inputTot, 2) ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$inputGst, 2) ?></td>

		</tr>

		<tr>
			<td class="text-right" colspan="3">GST Payable:</td>
			<td class="text-right"><?= number_format( (float)$outputGst - (float)$inputGst, 2) ?></td>

		</tr>
	<?php
		}	?>

	</tbody>

</table>
