<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<style media="print">
	@page {size: portrait; margin: 35px 35px 15px 35px; }
	</style>
	<table class="table table-sm">
		<thead class="small">
			<tr>
				<td style="width: 7em;">code</td>
				<td style="width: 7em;">type</td>
				<td>description</td>
				<td style="width: 12em;" class="text-right">value</td>
			</tr>

		</thead>

		<tbody>
			<?php
			$tot = 0;
			foreach( $this->data->dtoSet as $dto) {
				$tot += (int)$dto->glt_value;	?>
				<tr data-role="ledger-item" data-id="<?php print $dto->id ?>">
					<td><?php print $dto->gl_code ?></td>
					<td><?php print ( $dto->gl_trading ? 'Trade' : 'Balance') ?></td>
					<td><?php print $dto->gl_description ?></td>
					<td class="text-right"><?php print number_format( $dto->glt_value, 2) ?></td>

				</tr>

				<?php
			}	// foreach( $this->data->dtoset as $dto) ?>

		</tbody>

		<tfoot>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td class="text-right"><?= number_format( $tot, 2) ?></td>

			</tr>

		</tbody>

	</table>

	<div class="row d-print-none">
		<div class="col">
			<a class="btn btn-light" href="<?php url::write( 'ledger/edit') ?>">new account</a>

		</div>

	</div>
	<script>
	$(document).ready( function() {
		$('tr[data-role="ledger-item"]').each( function( i, el) {
			let _el = $(el);
			_el
			.addClass('pointer')
			.on( 'click', function( e) {
				window.location.href = _brayworth_.urlwrite( 'ledger/view/' + _el.data( 'id'));

			})

		});

	});
</script>
