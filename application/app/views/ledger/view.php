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
	<div class="row form-group">
		<div class="col-3">code</div>

		<div class="col-9 col-md-6 col-lg-4 col-xl-3">
			<div class="form-control">
				<?= $dto->gl_code ?>

			</div>

		</div>

	</div>

	<div class="row form-group">
		<div class="col-3">description</div>
		<div class="col-9">
			<div class="form-control">
				<?= $dto->gl_description ?>

			</div>

		</div>

	</div>

	<div class="row form-group">
		<div class="offset-3 col-9">
			<div class="row">
				<div class="col">
					<div class="form-control text-center">
						<?= dao\ledger::ledgerType( $dto->gl_trading, $dto->gl_type) ?>

					</div>

				</div>

				<div class="col">
					<div class="form-control text-center">
						<?= $dto->gl_trading ? 'trading' : 'balance sheet' ?>

					</div>

				</div>

			</div>

		</div>

	</div>


	<div class="row form-group">
		<div class="offset-3 col-9">
			<a href="<?php url::write('ledger/edit/' . $dto->id ) ?>" class="btn btn-outline-secondary">edit</a>

		</div>

	</div>

	<div class="row form-group">
		<div class="col-3"><?= $dto->gl_trading ? 'value' : 'balance' ?></div>
		<div class="col-9 col-md-6 col-lg-4 col-xl-3">
			<div class="form-control">
				<?= number_format( $dto->balance->balance, 2) ?>

			</div>

		</div>

	</div>

	<div class="row form-group">
		<div class="offset-3 col-9">
			<a href="<?php url::write('transactions/account/' . $dto->id ) ?>" class="btn btn-outline-secondary">view transactions</a>

		</div>

	</div>
