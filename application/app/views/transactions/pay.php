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
<div class="container">
	<form id="<?= $uidFrm = uniqid() ?>">
		<input type="hidden" name="glt_type" value="payment" />

		<div class="row form-group">
			<label class="col-2 col-form-label" for="<?= $uidDate = uniqid() ?>">
				date

			</label>

			<div class="col col-md-6">
				<input type="date" class="form-control" name="glt_date"
					id="<?= $uidDate ?>"
					value="<?= $this->data->glt_date ?>" />

			</div>

			<div class="col">
				<div class="input-group">
					<input type="text" class="form-control" name="glt_refer"
						value="<?= $this->data->glt_refer ?>" />

					<div class="input-group-append">
						<div class="input-group-text">refer</div>

					</div>

				</div>

			</div>

		</div>

		<div class="row form-group">
			<label class="col-2 col-form-label" for="h_glt_code">
				source

			</label>

			<div class="col col-md-6">
				<input type="text" class="form-control" name="h_glt_code" id="h_glt_code"
					value="<?= $this->data->glt_code ?>" />

			</div>

		</div>

		<div class="row form-group">
			<label class="col-2 col-form-label" for="<?= $uidGltValue = uniqid() ?>">
				value

			</label>

			<div class="col">
				<input type="text" class="form-control" name="h_glt_value"
					id="<?= $uidGltValue ?>"
					value="<?= $this->data->glt_value ?>" />

			</div>

			<div class="col-4 pl-0">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">$</div>

					</div>

					<input type="text" class="form-control" name="h_glt_gst"
					 	id="<?= $uidGstValue = uniqid() ?>"
						 value="<?= $this->data->glt_gst ?>" />


				</div>

			</div>

		</div>

		<div class="row form-group">
			<label class="col-2 col-form-label" for="h_glt_comment">
				payee

			</label>

			<div class="col">
				<input type="text" class="form-control" name="h_glt_comment"
					id="h_glt_comment" value="<?= $this->data->glt_comment ?>" />

			</div>

		</div>

		<div class="row">
			<div class="col" id="<?= $uidJournal = uniqid() ?>">
				<div class="row">
					<div class="col-5 col-lg-2">code</div>
					<div class="col-7 col-lg-5">description</div>
					<div class="d-none d-lg-block col-lg-3">value</div>
					<div class="d-none d-lg-block col-lg-2">gst</div>

				</div>

<?php	foreach ( $this->data->lines as $l) {
			print '<div class="row form-group" line>';
			printf( '<div class="col-5 col-lg-2"><input type="text" class="form-control" name="glt_code[]" value="%s" /></div>', $l->glt_code);
			printf( '<div class="col-7 col-lg-5"><input type="text" class="form-control" name="glt_comment[]" value="%s" /></div>', $l->glt_comment);
			printf( '<div class="col-7 col-lg-3">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">$</div>
					</div>
					<input type="text" class="form-control text-right" name="glt_value[]" value="%s" />
				</div>

			</div>', $l->glt_value);

			printf( '<div class="col-5 col-lg-2 pl-lg-0">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">$</div>
					</div>
					<input type="text" class="form-control text-right" name="glt_gst[]" value="%s" />
				</div>

			</div>', $l->glt_gst);

			print '</div>';
		}	?>

			</div>

		</div>

		<div class="row">
			<div class="col-lg-5">
				<div class="row">
					<div class="col">
						<button class="btn btn-outline-primary" id="<?= $uidBtnSave = uniqid('dvc_') ?>">save transaction</button>

					</div>

					<div class="col">
						<a class="btn btn-light" href="#" id="<?= $uidBtnAddLine = uniqid('dvc_') ?>">add line <i class="fa fa-fw fa-plus"></i></a>

					</div>

				</div>

			</div>

			<div class="col-3 col-lg-2 pt-lg-0 pt-2">un allocated</div>
			<div class="col-5 col-lg-3 pt-lg-0 pt-2">
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<div class="input-group-text">
							value

						</div>

					</div>

					<input type="text" class="form-control text-right" readonly id="<?= $uidTotDisplay = uniqid() ?>" />

				</div>

			</div>

			<div class="col-4 col-lg-2 pt-lg-0 pt-2 pl-lg-0">
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<div class="input-group-text">
							gst

						</div>

					</div>

					<input type="text" class="form-control text-right" readonly id="<?= $uidGstDisplay = uniqid() ?>" />

				</div>

			</div>

		</div>

	</form>

</div>

<script>
$(document).ready( function() {
	let codeFill = function( code) {
		code.autofill({
			autoFocus : true,
			source: function( request, response ) {
				$.ajax({
					url : _brayworth_.url( 'search/ledger'),
					data : { term: request.term },

				})
				.done( response);

			}

		});

	}

	let totLines = function() {
		let gltValue = $('#<?= $uidGltValue ?>');
		let v = Number( gltValue.val());
		if ( isNaN( v)) v = 0;

		let tot = v;

		gltValue.val( v.formatCurrency());
		//~ console.log( 'journal value', v);

		let gstValue = $('#<?= $uidGstValue ?>');
		let g = Number( gstValue.val());
		if ( isNaN( g)) g = 0;

		let gst = g;

		gstValue.val( g.formatCurrency());

		//~ console.log( 'journal values', v, g);
			//~ return ( tot);

		let lines = $('#<?= $uidJournal ?> [line]');
		if ( lines.length > 0) {
			lines.each( function( i, el) {
				let gltValue = $('input[name="glt_value[]"]', el);
				let v = Number( gltValue.val());
				if ( isNaN(v)) v = 0;

				gltValue.val( v.formatCurrency());
				tot -= v;
				//~ console.log( 'line value', v);

				let gstValue = $('input[name="glt_gst[]"]', el);
				let g = Number( gstValue.val());
				if ( isNaN(g)) g = 0;

				gstValue.val( g.formatCurrency());

				tot -= g;
				gst -= g;

				//~ console.log( 'line value', v, g);

			});

			// console.log( 'v, g');
			$('#<?= $uidBtnSave ?>').prop( 'disabled', tot != 0);

		}
		else {
			$('#<?= $uidBtnSave ?>').prop( 'disabled', true);

		}

		$('#<?= $uidGstDisplay ?>').val( (-gst).formatCurrency());
		$('#<?= $uidTotDisplay ?>').val( (-tot).formatCurrency());

		return ( tot);

	}

	$('#<?= $uidFrm ?>').on('submit', function() {
		return ( false);

	});

	$('#<?= $uidBtnSave ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		let frm = $('#<?= $uidFrm ?>');
		let data = frm.serializeFormJSON();
		data.action = 'save transaction';
		data.format = 'json';

		_brayworth_.post({
			url : _brayworth_.url('transactions'),
			data : data

		}).then( function( d) {
			_brayworth_.growl( d);
			frm.closest('.modal').modal('hide');

		});

	});

	totLines();	// disable submit button

	$('#<?= $uidBtnAddLine ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();
		console.log( 'add line');

		let code = $('<input type="text" class="form-control" name="glt_code[]" />');
		let comment = $('<input type="text" class="form-control" name="glt_comment[]" />');
		let value = $('<input type="text" class="form-control text-right" name="glt_value[]" />');
		let gst = $('<input type="text" class="form-control text-right" name="glt_gst[]" />');
		value.on('change', totLines);
		gst.on('change', totLines);

		let row = $('<div class="row form-group" line />').appendTo( $('#<?= $uidJournal ?>'));
		$('<div class="col-5 col-lg-2" />').append( code).appendTo( row);
		$('<div class="col-7 col-lg-5" />').append( comment).appendTo( row);

		(function() {
			let col = $('<div class="col-7 col-lg-3" />').appendTo( row);
			let ig = $('<div class="input-group" />').append( value).appendTo( col);

			ig.prepend( '<div class="input-group-prepend"><div class="input-group-text">$</div></div>');

		})();

		(function() {
			let col = $('<div class="col-5 col-lg-2 pl-lg-0" />').appendTo( row);
			let ig = $('<div class="input-group" />').append( gst).appendTo( col);

			ig.prepend( '<div class="input-group-prepend"><div class="input-group-text">$</div></div>');

		})();

		codeFill( code);

	});

	(function() {
		codeFill( $('#h_glt_code'));

		let lines = $('#<?= $uidJournal ?> [line]');
		if ( lines.length > 0) {
			lines.each( function( i, el) {
				codeFill( $('input[name="glt_code[]"]', el));
				$('input[name="glt_value[]"]', el).on('change', totLines);
				$('input[name="glt_gst[]"]', el).on('change', totLines);

			});

		}

		$('#<?= $uidGltValue ?>, #<?= $uidGstValue ?>').on('change', totLines);

	})();

});
</script>
