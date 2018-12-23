<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h1 class="d-none d-print-block"><?= $this->title ?></h1>

	<form class="row form-group d-print-none">
		<div class="offset-md-7 col-md-5">
			<div class="input-group">
				<input class="form-control" type="date" name="end" value="<?= $this->data->end ?>" />

				<div class="input-group-append">
					<button class="btn"><i class="fa fa-refresh"></i></button>

				</div>

			</div>

		</div>

	</form>
