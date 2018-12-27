<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<h4><a href="<?php url::write( 'transactions') ?>">transactions</a></h4>

<ul class="list-unstyled">
	<li><a href="#" id="<?= $uidReceipt = uniqid() ?>">receipt</a></li>
	<li><a href="#" id="<?= $uidPay = uniqid() ?>">pay</a></li>
	<li><a href="<?php url::write('transactions/edit/') ?>">journal</a></li>
	<li><hr /></li>
	<li><a href="<?php url::write('transactions/gst/') ?>">gst</a></li>
	<li><hr /></li>

</ul>
<script>
$(document).ready( function() {
	$('#<?= $uidReceipt ?>').on( 'click', function(e) {
		e.stopPropagation(); e.preventDefault();
		_brayworth_.loadModal({ url : _brayworth_.url('transactions/receipt')});

	});

	$('#<?= $uidPay ?>').on( 'click', function(e) {
		e.stopPropagation(); e.preventDefault();
		_brayworth_.loadModal({ url : _brayworth_.url('transactions/pay')});

	});

});
</script>
