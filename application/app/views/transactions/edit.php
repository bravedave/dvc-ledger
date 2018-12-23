<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Description:
		General Journal HTML Template

	*/	?>
<form method="POST" action="<?php url::write( 'transactions') ?>" id="<?= $uidFrm = uniqid( 'dvc_') ?>">
	<input type="hidden" name="glt_type" value="journal" />

	<table class="table table-sm" id="glt-journal">
		<thead>
			<tr>
				<td>date</td>
				<td>
					<input type="date" class="form-control" name="glt_date" value="<?php print $this->data->glt_date ?>" />

				</td>

				<td colspan="2">
					<div class="input-group">
						<input type="text" class="form-control" name="glt_refer" value="<?php print $this->data->glt_refer ?>" />

						<div class="input-group-append">
							<div class="input-group-text">refer</div>

						</div>

					</div>

				</td>

			</tr>

			<tr>
				<td style="width: 10rem;">code</td>
				<td style="width: 20rem;">description</td>
				<td style="width: 10rem;">value</td>
				<td style="width: 10rem;">gst</td>

			</tr>

		</thead>

		<tbody id="<?= $uidTBody = uniqid('dvc_'); ?>">
			<?php	foreach ( $this->data->lines as $l) {
				print '<tr>';
				printf( '<td><input type="text" class="form-control" name="glt_code[]" value="%s" /></td>', $l->glt_code);
				printf( '<td><input type="text" class="form-control" name="glt_comment[]" value="%s" /></td>', $l->glt_comment);
				printf( '<td><input type="text" class="form-control text-right" name="glt_value[]" value="%s" /></td>', $l->glt_value);
				printf( '<td><input type="text" class="form-control text-right" name="glt_gst[]" value="%s" /></td>', $l->glt_gst);
				print '</tr>';

			}	?>

		</tbody>

		<tfoot>
			<tr>
				<td>
					<a class="btn btn-light" href="#" id="<?= $uidAddLine = uniqid('dvc_'); ?>">
						add line <i class="fa fa-fw fa-plus"></i>

					</a>

				</td>

				<td class="text-right">GST</td>
				<td class="text-right" id="<?= $uidTotalGst = uniqid('dvc_'); ?>">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td><input class="btn btn-outline-primary" type="submit" name="action" value="save transaction" id="<?= $uidSaveBtn = uniqid('dvc_'); ?>" /></td>
				<td class="text-right">Journal Total</td>
				<td class="text-right" id="<?= $uidTotal = uniqid('dvc_'); ?>">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

		</tfoot>

	</table>

</form>
<script>
$(document).ready( function() {
	$('#<?= $uidTBody ?>').on( 'total-lines', function( e) {
		let tot = 0
		let gst = 0
		let lines = $('>tr', this);
		if ( lines.length > 0) {
			lines.each( function( i, tr) {
				let gltValue = $('input[name="glt_value[]"]', tr);
				let gstValue = $('input[name="glt_gst[]"]', tr);

				let v = Number( gltValue.val());
				if ( isNaN(v)) v = 0;

				let g = Number( gstValue.val());
				if ( isNaN(v)) g = 0;

				gltValue.val( v.formatCurrency());
				tot += v;

				gstValue.val( g.formatCurrency());
				gst += g;
				tot += g;

				//~ console.log( v, g);

			});

			$('#<?= $uidSaveBtn ?>').prop( 'disabled', tot != 0);

		}
		else {
			$('#<?= $uidSaveBtn ?>').prop( 'disabled', true);

		}
		$('#<?= $uidTotalGst ?>').html( gst.formatCurrency());
		$('#<?= $uidTotal ?>').html( tot.formatCurrency());
		return ( tot);

	});

	let codeAutoComplete = function() {
		let _tr = this.closest('tr');
		let comment = $( 'input[name="glt_comment[]"]', _tr);
		let value = $( 'input[name="glt_value[]"]', _tr);

		this.autocomplete({
			autoFocus : true,
			source: function( request, response ) {
				$.ajax({
					url : _brayworth_.urlwrite( 'search/ledger'),
					data : { term: request.term },
					success: response,

				})

			},
			minLength: 2,
			select: function(event, ui) {
				var o = ui.item;
				comment.val( o.label);
				setTimeout( function() { value.focus()}, 100);

			}

		})

	}

	$('#<?= $uidTBody ?> > tr').each( function( i, tr) {
		let _tr = $( tr);
		let code = $( 'input[name="glt_code[]"]', _tr);
		let value = $( 'input[name="glt_value[]"]', _tr);
		let gst = $( 'input[name="glt_gst[]"]', _tr);

		value.on('change', function(e) { $('#<?= $uidTBody ?>').trigger( 'total-lines') });
		gst.on('change', function(e) { $('#<?= $uidTBody ?>').trigger( 'total-lines') });

		codeAutoComplete.call( code);

	});

	$('#<?= $uidFrm ?>').on('submit', function( e) {
		$('#<?= $uidTBody ?>').trigger( 'total-lines');
		return ( !$('#<?= $uidSaveBtn ?>').prop( 'disabled'));

	});

	$('#<?= $uidTBody ?>').on( 'addline', function( e) {
		let value = $('<input type="text" class="form-control text-right" name="glt_value[]" value="" />');
		let comment = $('<input type="text" class="form-control" name="glt_comment[]" value="" />');
		let code = $('<input type="text" class="form-control" name="glt_code[]" value="" />');
		let gst = $('<input type="text" class="form-control" name="glt_gst[]" value="" />');

		let tr = $('<tr />').appendTo( this);
		$('<td />').append( code).appendTo( tr);
		$('<td />').append( comment).appendTo( tr);
		$('<td />').append( value).appendTo( tr);
		$('<td />').append( gst).appendTo( tr);

		value.on('change', function(e) { $('#<?= $uidTBody ?>').trigger( 'total-lines') });
		gst.on('change', function(e) { $('#<?= $uidTBody ?>').trigger( 'total-lines') });
		codeAutoComplete.call( code);

	});

	$('#<?= $uidAddLine ?>').on( 'click', function( e) { $('#<?= $uidTBody ?>').trigger( 'addline'); });

	$('#<?= $uidTBody ?>').trigger( 'total-lines');

});
</script>
