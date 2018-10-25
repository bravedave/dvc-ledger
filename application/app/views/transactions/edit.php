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
<form method="POST" action="<?php url::write( 'transactions') ?>">
	<input type="hidden" name="glt_type" value="journal" />
	<table class="table table-striped table-sm" id="glt-journal">
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
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td>refer</td>
				<td>
					<input type="text" class="form-control" name="glt_refer" value="<?php print $this->data->glt_refer ?>" />

				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

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
				<td>&nbsp;</td>
				<td class="text-right">GST</td>
				<td class="text-right" data-role="gst">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td class="text-right"><input class="btn btn-default" data-role="save-button" type="submit" name="action" value="save transaction" /></td>
				<td>&nbsp;</td>
				<td class="text-right">Journal Total</td>
				<td class="text-right" data-role="total">&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

		</tfoot>

	</table>

</form>
<script>
$(document).ready( function() {
	function totLines() {
		var tot = 0
		var gst = 0
		var lines = $('#glt-journal tbody tr');
		if ( lines.length > 0) {
			lines.each( function( i, el) {
				var gltValue = $('input[name="glt_value[]"]', el);
				var v = Number( gltValue.val());
				if ( isNaN(v))
					v = 0;
				gltValue.val( v.formatCurrency());
				tot += v;

				var gstValue = $('input[name="glt_gst[]"]', el);
				var g = Number( gstValue.val());
				if ( isNaN(v))
					g = 0;
				gstValue.val( g.formatCurrency());
				gst += g;
				tot += g;

				//~ console.log( v, g);

			});

			$('input[data-role="save-button"]').prop( 'disabled', tot != 0);

		}
		else {
			$('input[data-role="save-button"]').prop( 'disabled', true);

		}
		$('#glt-journal tfoot td[data-role="gst"]').html( gst.formatCurrency());
		$('#glt-journal tfoot td[data-role="total"]').html( tot.formatCurrency());
		return ( tot);

	}

	$('#glt-journal tbody input[name="glt_code[]"]').each( function( i, el) {
		var _el = $(el);
		var tr = _el.closest( 'tr');
		var comment = $( 'input[name="glt_comment[]"]', tr);
		var value = $( 'input[name="glt_value[]"]', tr);
		var gst = $( 'input[name="glt_gst[]"]', tr);

		value.on('change', totLines);
		gst.on('change', totLines);

		_el.autocomplete({
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

	});

	$('input[name="glt_date"]').closest('form').on('submit', function( e) {
		totLines();
		return ( !$('input[data-role="save-button"]').prop( 'disabled'));

	});

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
						success: response,

					})

				},
				minLength: 2,
				select: function(event, ui) {
					var o = ui.item;
					//~ comment.val( o.label);
					value.focus()

				}

			})

		})

	});

	totLines();	// disable submit button

});
</script>
