<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h1 class="d-none d-print-block"><?= $this->title ?></h1>

	<form class="row d-print-none">
		<div class="offset-md-2 col-md-10 offset-lg-3 col-lg-9 offset-xl-4 col-xl-8">
			<div class="row form-group">
				<div class="col pr-0">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<div class="input-group-text">start</div>

						</div>

						<input class="form-control" type="date" name="start" value="<?= $this->data->start ?>" />

					</div>

				</div>

				<div class="col pr-0">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<div class="input-group-text">end</div>

						</div>

						<input class="form-control" type="date" name="end" value="<?= $this->data->end ?>" />

					</div>

				</div>

				<div class="col">
					<div class="row">
						<div class="col">
							<div class="form-check form-control-sm">
								<input type="checkbox" class="form-check-input" name="paid" value="yes" <?php if ( $this->data->paid) print "checked" ?> />

								<label class="form-check-label">Paid</label>

							</div>

						</div>

						<div class="col">
							<button class="btn btn-block btn-outline-secondary btn-sm"><i class="fa fa-refresh"></i></button>

						</div>

					</div>

				</div>

			</div>

		</div>

	</form>
