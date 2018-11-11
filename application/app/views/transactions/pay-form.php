<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Description:
		Payment Form

	*/	?>
<form method="POST" action="<?php url::write( 'transactions') ?>">
	<input type="hidden" name="glt_type" value="payment" />
	<table class="table-sm" id="glt-journal">
		<colgroup>
			<col style="width: 10rem;" />
			<col style="width: 20rem;" />
			<col />
			<col style="width: 10rem;" />
			<col style="width: 10rem;" />

		</colgroup>

		<thead>
			<tr>
				<td>date</td>
				<td>
					<input type="date" class="form-control" name="glt_date" value="<?php print $this->data->glt_date ?>" />

				</td>
				<td colspan="3">&nbsp;</td>

			</tr>

			<tr>
				<td>refer</td>
				<td>
					<input type="text" class="form-control" name="glt_refer" value="<?php print $this->data->glt_refer ?>" />

				</td>
				<td colspan="3">&nbsp;</td>

			</tr>

			<tr>
				<td>source</td>
				<td>
					<input type="text" class="form-control" name="h_glt_code" id="h_glt_code" value="<?php print $this->data->glt_code ?>" />

				</td>
				<td colspan="3">&nbsp;</td>

			</tr>

			<tr>
				<td>value</td>
				<td>
					<input type="text" class="form-control" name="h_glt_value" value="<?php print $this->data->glt_value ?>" />

				</td>
				<td class="text-right">gst</td>
				<td>
					<input type="text" class="form-control" name="h_glt_gst" value="<?php print $this->data->glt_gst ?>" />

				</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td>payee</td>
				<td colspan="4">
					<input type="text" class="form-control" name="h_glt_comment" value="<?php print $this->data->glt_comment ?>" />

				</td>

			</tr>

			<tr>
				<td>code</td>
				<td colspan="2">description</td>
				<td>value</td>
				<td>gst</td>

			</tr>

		</thead>

		<tbody>
<?php	foreach ( $this->data->lines as $l) {
			print '<tr>';
			printf( '<td><input type="text" class="form-control" name="glt_code[]" value="%s" /></td>', $l->glt_code);
			printf( '<td colspan="2"><input type="text" class="form-control" name="glt_comment[]" value="%s" /></td>', $l->glt_comment);
			printf( '<td><input type="text" class="form-control text-right" name="glt_value[]" value="%s" /></td>', $l->glt_value);
			printf( '<td><input type="text" class="form-control text-right" name="glt_gst[]" value="%s" /></td>', $l->glt_gst);
			print '</tr>';
		}	?>

		</tbody>

		<tfoot>
			<tr>
				<td><a href="#" data-role="add-line">[add line]</a></td>
				<td class="text-right" colspan="2">GST</td>
				<td class="text-right" data-role="gst">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td class="text-right"><input class="btn btn-default" data-role="save-button" type="submit" name="action" value="save transaction" /></td>
				<td class="text-right" colspan="2">un allocated</td>
				<td class="text-right" data-role="total">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

		</tfoot>

	</table>

</form>
<script>
	function totLines() {
		var tot = 0
		var gst = 0
		var gltValue = $('thead input[name="h_glt_value"]');
		var v = Number( gltValue.val());
		if ( isNaN( v))
			v = 0;
		tot += v;
		gltValue.val( v.formatCurrency());

		var gstValue = $('thead input[name="h_glt_gst"]');
		var g = Number( gstValue.val());
		if ( isNaN( g))
			g = 0;
		gstValue.val( g.formatCurrency());

		//~ return ( tot);

		var lines = $('#glt-journal tbody tr');
		if ( lines.length > 0) {
			lines.each( function( i, el) {
				var gltValue = $('input[name="glt_value[]"]', el);
				var v = Number( gltValue.val());
				if ( isNaN(v))
					v = 0;
				gltValue.val( v.formatCurrency());
				tot -= v;

				var gstValue = $('input[name="glt_gst[]"]', el);
				var g = Number( gstValue.val());
				if ( isNaN(v))
					g = 0;
				gstValue.val( g.formatCurrency());
				gst -= g;
				tot -= g;

				// console.log( v, g);

			});

			// console.log( 'v, g');
			$('input[data-role="save-button"]').prop( 'disabled', tot != 0);

		}
		else {
			$('input[data-role="save-button"]').prop( 'disabled', true);

		}
		$('#glt-journal tfoot td[data-role="gst"]').html( (-gst).formatCurrency());
		$('#glt-journal tfoot td[data-role="total"]').html( (-tot).formatCurrency());
		return ( tot);

	}



$(document).ready( function() {
	$('input[data-role="save-button"]').prop( 'disabled', true);

	$('a[data-role="add-line"]').each( function( i, el) {
		var _el = $(el);
		_el
		.on( 'click', function( e) {
			var value = $('<input type="text" class="form-control text-right" name="glt_value[]" value="" />');
			var comment = $('<input type="text" class="form-control" name="glt_comment[]" value="" />');
			var code = $('<input type="text" class="form-control" name="glt_code[]" value="" />');

			var r = $('<tr />');
			$('<td />').append( code).appendTo( r);
			$('<td colspan="2" />').append( comment).appendTo( r);
			$('<td />').append( value).appendTo( r);

			r.appendTo('#glt-journal tbody');

			value.on('change', totLines);

			code.autocomplete({
				autoFocus : true,
				source: function( request, response ) {
					$.ajax({
						url : _brayworth_.urlwrite( 'search/ledger'),
						data : { term: request.term },

					})
					.done( response);

				},
				minLength: 2,
				select: function(event, ui) {
					var o = ui.item;
					//~ comment.val( o.label);
					value.focus()

				}

			})

		})

		$('#h_glt_code').autocomplete({
			autoFocus : true,
			source: function( request, response ) {
				$.ajax({
					url : _brayworth_.urlwrite( 'search/ledger'),
					data : { term: request.term },

				})
				.done( response);

			},
			minLength: 2,
			select: function(event, ui) {
				var o = ui.item;
				//~ comment.val( o.label);
				value.focus()

			}

		})

	});

	totLines();	// disable submit button

});
</script>
