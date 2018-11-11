<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<nav class="navbar sticky-top navbar-expand-md navbar-light bg-light">
	<a class="navbar-brand" href="#"><?php print $this->data->title; ?></a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#x<?php print $uid = uniqid() ?>"
		aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse justify-content-end" id="x<?php print $uid ?>">
		<div class="navbar-nav">
			<a class="nav-item nav-link" href="<?php url::write('ledger') ?>">ledger</a>
			<a class="nav-item nav-link" href="<?php url::write('transactions') ?>">transactions</a>
			<a class="nav-item nav-link" href="<?php url::write() ?>">home</a>

		</div>

	</div>

</nav>
