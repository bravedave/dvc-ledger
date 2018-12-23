<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<h4><a href="<?php url::write( 'ledger') ?>">ledger</a></h4>
<ul class="list-unstyled">
	<li><a href="<?php url::write( 'ledger/trial') ?>">trial balance</a></li>
	<li><a href="<?php url::write( 'ledger/balanceSheet') ?>">balance sheet</a></li>
	<li><a href="<?php url::write( 'ledger/trading') ?>">trading report</a></li>
	<li><a href="<?php url::write( 'transactions/gst') ?>">gst</a></li>
	<li><hr /></li>
</ul>
<?php $this->load('transactions/index'); ?>
