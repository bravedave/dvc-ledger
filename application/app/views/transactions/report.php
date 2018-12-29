<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<style>
	@media print{
		@page {size: portrait; margin: 40px 20px 40px 20px; }
		html { font-size: 12px; }
	}
	</style>

	<table class="table table-sm">
		<thead class="small">
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" class="text-center">transaction</td>
				<td colspan="2" class="text-center">gst</td>
			</tr>
			<tr>
				<td style="width: 7em">date</td>
				<td>description</td>
				<td style="width: 7em" class="text-center">debit</td>
				<td style="width: 7em" class="text-center">credit</td>
				<td style="width: 6em" class="text-center">debit</td>
				<td style="width: 6em" class="text-center">credit</td>
			</tr>

		</thead>

		<tbody>
			<?php
			$tot = 0;
			$debit = 0;
			$credit = 0;
			$totGST = 0;
			$debitGST = 0;
			$creditGST = 0;
			$sub_debit = 0;
			$sub_credit = 0;
			$sub_totGST = 0;
			$sub_debitGST = 0;
			$sub_creditGST = 0;
			$gl_code = '';
			$gl_description = '';
			$count = 0;
			foreach ( $this->data->dtoSet as $dto) {
				if ( $dto->glt_code != $gl_code) {
					if ( $count) {	?>
						<tr>
							<td class="text-right text-muted" colspan="2"><?= $gl_description ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_debit, 2) ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_credit, 2) ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_debitGST, 2) ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_creditGST, 2) ?></td>

						</tr>
						<?php
					}

					$sub_debit = 0;
					$sub_credit = 0;
					$sub_totGST = 0;
					$sub_debitGST = 0;
					$sub_creditGST = 0;
					$gl_code = $dto->glt_code;
					$gl_description = $dto->gl_description;
					?>
					<tr>
						<td colspan="6"><?= $dto->gl_description ?> (<?= $dto->glt_code ?>)</td>

					</tr>

					<?php
				}
				$count ++;
				$tot += (float)$dto->glt_value;
				$totGST += (float)$dto->glt_gst;
				$sub_totGST += (float)$dto->glt_gst;
				if ( (float)$dto->glt_value > 0) {
					$debit += (float)$dto->glt_value;
					$sub_debit += (float)$dto->glt_value;

				}
				else {
					$credit -= (float)$dto->glt_value;
					$sub_credit -= (float)$dto->glt_value;

				}

				if ( (float)$dto->glt_gst > 0) {
					$debitGST += (float)$dto->glt_gst;
					$sub_debitGST += (float)$dto->glt_gst;

				}
				else {
					$creditGST -= (float)$dto->glt_gst;
					$sub_creditGST -= (float)$dto->glt_gst;

				}

				?>
				<tr>
					<td><?= strings::asShortDate( $dto->glt_date)	?></td>
					<td><?= $dto->glt_comment	?></td>
					<td class="text-right"><?= ( (float)$dto->glt_value > 0 ? number_format( (float)$dto->glt_value, 2) : '&nbsp;' ) ?></td>
					<td class="text-right"><?= ( (float)$dto->glt_value < 0 ? number_format( -(float)$dto->glt_value, 2) : '&nbsp;' ) ?></td>
					<td class="text-right"><?= ( (float)$dto->glt_gst > 0 ? number_format( (float)$dto->glt_gst, 2) : '&nbsp;' ) ?></td>
					<td class="text-right"><?= ( (float)$dto->glt_gst < 0 ? number_format( -(float)$dto->glt_gst, 2) : '&nbsp;' ) ?></td>

				</tr>

				<?php
			}	// foreach ( $this->data->dtoSet as $dto)

			if ( $count) {	?>
				<tr>
					<td class="text-right text-muted" colspan="2"><?= $gl_description ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_debit, 2) ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_credit, 2) ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_debitGST, 2) ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_creditGST, 2) ?></td>

				</tr>
				<?php
			}	?>

		</tbody>

		<tfoot>
			<tr>
				<td class="text-right" colspan="2">debit/credit</td>
				<td class="text-right"><?= number_format( (float)$debit, 2) ?></td>
				<td class="text-right"><?= number_format( (float)$credit, 2) ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td class="text-right" colspan="2">gst</td>
				<td class="text-right"><?= number_format( (float)$debitGST, 2) ?></td>
				<td class="text-right"><?= number_format( (float)$creditGST, 2) ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td class="text-right" colspan="2">total</td>
				<td class="text-right"><?= number_format( (float)$debitGST + (float)$debit, 2) ?></td>
				<td class="text-right"><?= number_format( (float)$creditGST + (float)$credit, 2) ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

		</tfoot>

	</table>
