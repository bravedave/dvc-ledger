<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Outputs gl_type a
	Inputs gl_type b & c

	*/	?>
	<style>
	@media print{
		@page {size: portrait; margin: 40px 20px 40px 20px; }
	}
	</style>

<table class="table table-sm">
	<thead class="small">
		<tr>
			<td class="border-0">&nbsp;</td>
			<td class="border-0">&nbsp;</td>
			<td style="width: 8em;" class="text-right border-0">Value</td>
			<td style="width: 8em;" class="text-right border-0">GST</td>
			<td style="width: 5em;" class="border-0">&nbsp;</td>

		</tr>

	</thead>
	<tbody id="<?= $uidBody = uniqid('dvc_'); ?>">
		<tr>
			<td colspan="5" class="border-0">Outputs</td>

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
						<td colspan="5" class="border-0">Inputs</td>

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
				data-remittable="<?= dao\transactions::gst_paid == $dto->glt_gst_remit ? 'no' : 'yes' ?>"
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
				if ( dao\transactions::gst_paid == $dto->glt_gst_remit) {
					print 'paid';

				}
				elseif ( dao\transactions::gst_remitted == $dto->glt_gst_remit ) {
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
				<td class="text-right border-0" colspan="3">GST Payable:</td>
				<td class="text-right"><?= number_format( (float)$outputGst - (float)$inputGst, 2) ?></td>
				<td class="border-0">&nbsp;</td>

			</tr>
			<?php
		}	?>

		<tr>
			<td class="text-right border-0" colspan="3">GST Remitted:</td>
			<td class="text-right" id="<?= $uidRemitted = uniqid('dvc_'); ?>">&nbsp;</td>
			<td class="text-center border-0" id="<?= $uidRemitPay = uniqid('dvc_'); ?>">&nbsp;</td>

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
		let count = 0;

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

			count ++;

		});

		$('#<?= $uidRemitted ?>').html( tGST.formatCurrency());
		if ( count > 0) {

			if ( $('#<?= $uidRemitPay ?> > button').length < 1) {
				let btn = $('<button class="btn btn-sm btn-primary">pay</button>');
				btn.on( 'click', function( e) {
					e.stopPropagation(); e.preventDefault();
					_brayworth_.loadModal({ url : _brayworth_.url('transactions/paygst')});

				});

				$('#<?= $uidRemitPay ?>').html('').append( btn);

			}

		}
		else {
			$('#<?= $uidRemitPay ?>').html('&nbsp;');

		}

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
