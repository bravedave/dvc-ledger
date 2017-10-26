<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class ledger extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');
		if ( $action == 'add/update') {
			//~ sys::dump( $_POST);

			$id = (int)$this->getPost( 'id');
			$a = [
				'gl_description' => $this->getPost( 'gl_description'),
				'gl_trading' => (int)$this->getPost( 'gl_trading'),
			];

			$dao = new dao\ledger;
			if ( $id > 0) {
				$dao->UpdateByID( $a, $id);
				Response::redirect( url::tostring( 'ledger/trial'), 'updated');

			}
			else {
				if ( $a['gl_code'] = strtolower( $this->getPost( 'gl_code'))) {
					/* ensure code not a duplicate */
					if ( $dto = $dao->getByCode( $a['gl_code'])) {
						Response::redirect( url::tostring( 'ledger/trial'), 'code exists');

					}
					else {
						$dao->Insert( $a);
						Response::redirect( url::tostring( 'ledger/trial'), 'added');

					}

				}
				else {
					Response::redirect( url::tostring( 'ledger/trial'), 'code cannot be blank');

				}

			}

		}

	}

	function edit( $id = 0) {
		$this->data = (object)[
			'dto' => FALSE
		];

		if ( $id = (int)$id) {
			$dao = new dao\ledger;
			$this->data->dto = $dao->getByID( $id);

		}

		$p = new page( $this->title = 'create / edit account');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('edit');

			$p->secondary();
				$this->load('index');

	}

	function trial() {
		$dao = new dao\ledger;
		$this->data = (object)[
			'dtoSet' => $dao->trialBalance()
		];

		//~ sys::dump( $this->data);

		$p = new page( $this->title = 'trial balance');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('trial-balance');

			$p->secondary();
				$this->load('index');

	}

	protected function _index() {
		$p = new page( $this->title = 'ledger');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('blank');

			$p->secondary();
				$this->load('index');

	}

	function index() {
		if ( $this->isPost())
			$this->postHandler();

		else
			$this->_index();


	}

}