<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<h4>maintenance</h4>
<ul class="list-unstyled">
	<li><a href="<?= strings::url('users') ?>">users</a></li>
	<li><a href="<?= strings::url('settings') ?>">settings</a></li>
	<li><a href="<?= strings::url('home/dbinfo') ?>">dbinfo</a></li>
<?php
	if ( qbimport::files()) {	?>
	<li><a href="<?= strings::url('import') ?>">QB Import</a></li>
<?php
	}	?>
	<li><hr /></li>
<?php
	if ( $this->Request->ServerIsLocal()) {	?>
	<li><a href="<?= strings::url('info') ?>">info</a></li>

<?php
	}	// if ( $this->Request->ServerIsLocal())	?>
	<li><a href="<?= strings::url('docs') ?>">docs</a></li>

</ul>
