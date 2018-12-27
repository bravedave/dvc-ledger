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
	<thead class="small">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right">Value</td>
			<td class="text-right">GST</td>
			<td style="width: 3em;">&nbsp;</td>

		</tr>

	</thead>
	<tbody id="<?= $uidBody = uniqid('dvc_'); ?>">
		<tr>
			<td colspan="5">Outputs</td>

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
						<td>&nbsp;</td>

					</tr>

					<tr>
						<td colspan="5">Inputs</td>

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
			<tr
			data-remittable="yes"
			data-id="<?= $dto->id ?>"
			data-output="<?= ( $output ? 'yes' : 'no' ) ?>"
			data-value="<?= number_format( (float)$dto->glt_value*$factor, 2); ?>"
			data-gst="<?= number_format( (float)$dto->glt_gst*$factor, 2); ?>"
			>
			<td><?= strings::asShortDate( $dto->glt_date); ?></td>
			<td><?= $dto->glt_comment; ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$dto->glt_value*$factor, 2); ?></td>
			<td class="text-right text-muted"><?= number_format( (float)$dto->glt_gst*$factor, 2); ?></td>
			<td class="text-center" remit><?php
				if ( $dto->glt_gst_remit == dao\transactions::gst_remitted ) {
					printf( '<i class="fa fa-check" data-remit="yes" data-id="%s"></i>', $dto->id);

				}
				else {
					print '&nbsp;';

				}
				?></td>

			</tr>

			<?php
		}	?>
				
	</tbody>

	<tfoot>
		<?php
		if ( $count) {	?>
			<tr>
				<td class="text-right text-muted" colspan="2">Total Inputs</td>
				<td class="text-right text-muted"><?= number_format( (float)$inputTot, 2) ?></td>
				<td class="text-right text-muted"><?= number_format( (float)$inputGst, 2) ?></td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td class="text-right" colspan="3">GST Payable:</td>
				<td class="text-right"><?= number_format( (float)$outputGst - (float)$inputGst, 2) ?></td>
				<td>&nbsp;</td>

			</tr>
			<?php
		}	?>

		<tr>
			<td class="text-right" colspan="3">GST Remitted:</td>
			<td class="text-right" id="<?= $uidRemitted = uniqid('dvc_'); ?>">&nbsp;</td>
			<td>&nbsp;</td>

		</tr>

	</tfoot>

</table>
<script>
$(document).ready(function() {
	let remitState = function( state) {
		let _tr = $(this);

		_brayworth_.post({
				url : _brayworth_.url('transactions'),
				data : {
					action : 'gst_remit',
					id : _tr.data('id'),
					value : state ? 1 : 0,

				}

		}).then( function( d) {
			_brayworth_.growl( d);
			$('#<?= $uidBody ?>').trigger( 'remit-total');

		});

	}

	$('#<?= $uidBody ?>').on( 'remit-total', function( e) {
		let tGST = 0;

		$('[data-remit="yes"]', this).each( function( i, icon) {
			let _r = $(icon).closest('tr');
			let output = _r.data('output') == 'yes';
			let g = Number( _r.data('gst'));
			if ( output) {
				tGST += g;

			}
			else {
				tGST -= g;

			}

		});

		$('#<?= $uidRemitted ?>').html( tGST.formatCurrency());

	});

	$('#<?= $uidBody ?> > tr').each( function( i, tr) {
		let _tr = $(tr);
		if ( _tr.data('remittable') == 'yes') {
			$('td[remit]', _tr).addClass('pointer').on( 'click', function( e) {
				let ctrl = $('i.fa', this);

				if ( ctrl.length > 0) {
					ctrl.remove();
					remitState.call( _tr, false);

				}
				else {
					ctrl = $('<i class="fa fa-check" data-remit="yes" />');
					ctrl.data('id', _tr.data('id'));

					$(this).html('').append( ctrl);
					remitState.call( _tr, true);

				}

			});

		}

	});

	$('#<?= $uidBody ?>').trigger( 'remit-total');

})
</script>
