<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-striped table-sm">
	<colgroup>
		<col style="width: 7em;" />
		<col />
		<col style="width: 12em;" />

	</colgroup>

	<thead>
		<tr>
			<td>code</td>
			<td>description</td>
			<td class="text-center">value</td>
		</tr>

	</thead>

	<tbody>
<?php	$tot = 0;
		foreach( $this->data->dtoSet as $dto) {
			$tot += (int)$dto->glt_value;	?>
		<tr data-role="ledger-item" data-id="<?php print $dto->id ?>">
			<td><?php print $dto->gl_code ?></td>
			<td><?php print $dto->gl_description ?></td>
			<td class="text-right"><?php print number_format( $dto->glt_value, 2) ?></td>

		</tr>

<?php	}	// foreach( $this->data->dtoset as $dto) ?>

	</tbody>

	<tfoot>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-right"><?php print number_format( $tot, 2) ?></td>

		</tr>

	</tbody>

</table>

<div class="row">
	<div class="col">
		[<a href="<?php url::write( 'ledger/edit') ?>">new account</a>]

	</div>

</div>
<script>
$(document).ready( function() {
	$('tr[data-role="ledger-item"]').each( function( i, el) {
		var _el = $(el);
		_el
		.css({'cursor':'pointer'})
		.on( 'click', function( e) {
			window.location.href = _brayworth_.urlwrite( 'ledger/edit/' + _el.data( 'id'));

		})

	});

});
</script>