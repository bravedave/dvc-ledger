<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

	$dto = (object)[
		'id' => 0,
		'gl_code' => '',
		'gl_description' => '',
		'gl_type' => ''

	];

	if ( $this->data->dto) {
		$dto = $this->data->dto;

	}
	?>
	<form class="form" method="post" action="<?php url::write('ledger') ?>">
		<input type="hidden" name="id" value="<?= $dto->id ?>" />

		<div class="row">
			<div class="col-3">code</div>

			<div class="col-9">
				<input type="text" class="form-control" name="gl_code" value="<?= $dto->gl_code ?>"
				<?php if ( $dto->id > 0) print 'disabled'; ?> />

			</div>

		</div>

		<div class="row pt-4">
			<div class="col-3">description</div>
			<div class="col-9">
				<input type="text" class="form-control" name="gl_description" value="<?= $dto->gl_description ?>" />

			</div>

		</div>

		<div class="row pt-4">
			<div class="offset-3 col-9">
				<label>
					<input type="checkbox" class="form-control-checkbox" name="gl_trading" <?php if ( $dto->gl_trading) print 'checked' ?> value="1" />
					trading account

				</label>

			</div>

		</div>

		<div class="row pt-4">
			<div class="col-3">account type</div>
			<div class="col-9">
				<select class="form-control" name="gl_type" data-value="<?= $dto->gl_type ?>"></select>

			</div>

		</div>

		<div class="row pt-4">
			<div class="offset-3 col-9">
				<input type="submit" class="btn btn-primary" name="action" value="add/update" />

			</div>

		</div>

	</form>
	<script>

		$(document).ready( function() {
			function setupTrading() {
				let sel = $('select[name="gl_type"]')
				sel.html('');
				$('<option value="">type</option>').appendTo( sel);
				$.each( ledger.sections.trading, function( i, el) {
					$('<option />').attr('value', i).html( el).appendTo( sel);

				})

				sel.val( sel.data('value'));

			}

			function setupBalsheet() {
				let sel = $('select[name="gl_type"]');
				sel.html('');
				$('<option value="">type</option>').appendTo( sel);
				$.each( ledger.sections.balsheet, function( i, el) {
					$('<option />').attr('value', i).html( el).appendTo( sel);

				})

				sel.val( sel.data('value'));

			}

			function checkSelState() {
				$(this).prop( 'checked') ? setupTrading() : setupBalsheet();

			}

			var t = $('input[name="gl_trading"]')
			t.on( 'change', checkSelState);
			checkSelState.call( t);

		})
	</script>
