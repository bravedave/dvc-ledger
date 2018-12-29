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
	}
	</style>

	<table class="table table-sm">
		<thead class="small">
			<tr>
				<td style="width: 7em" class="border-0">date</td>
				<td class="border-0">description</td>
				<td style="width: 7em" class="text-center border-0">value</td>
				<td style="width: 7em" class="text-center border-0">gst</td>

			</tr>

		</thead>

		<tbody>
			<?php
			$tot = 0;
			$gst = 0;
			$sub_tot = 0;
			$sub_gst = 0;
			$gl_code = '';
			$gl_description = '';
			$count = 0;
			$countCodes = 0;
			foreach ( $this->data->dtoSet as $dto) {
				if ( $dto->glt_code != $gl_code) {
					if ( $count) {	?>
						<tr>
							<td class="text-right text-muted" colspan="2"><?= $gl_description ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_tot, 2) ?></td>
							<td class="text-right text-muted"><?= number_format( (float)$sub_gst, 2) ?></td>

						</tr>
						<?php
					}

					$countCodes ++;
					$sub_tot = 0;
					$sub_gst = 0;
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
				$sub_tot += (float)$dto->glt_value;
				$gst += (float)$dto->glt_gst;
				$sub_gst += (float)$dto->glt_gst;

				?>
				<tr>
					<td><?= strings::asShortDate( $dto->glt_date)	?></td>
					<td><?= $dto->glt_comment	?></td>
					<td class="text-right"><?= number_format( (float)$dto->glt_value, 2) ?></td>
					<td class="text-right"><?= number_format( (float)$dto->glt_gst, 2) ?></td>

				</tr>

				<?php
			}	// foreach ( $this->data->dtoSet as $dto)

			if ( $countCodes > 1) {	?>
				<tr>
					<td class="text-right text-muted" colspan="2"><?= $gl_description ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_tot, 2) ?></td>
					<td class="text-right text-muted"><?= number_format( (float)$sub_gst, 2) ?></td>

				</tr>
				<?php
			}	?>

		</tbody>

		<tfoot>
			<tr>
				<td class="text-right" colspan="2">total</td>
				<td class="text-right"><?= number_format( (float)$tot, 2) ?></td>
				<td class="text-right"><?= number_format( (float)$gst, 2) ?></td>

			</tr>

		</tfoot>

	</table>
