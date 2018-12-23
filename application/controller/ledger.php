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
				'gl_type' => $this->getPost( 'gl_type'),
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

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	function edit( $id = 0) {
		$this->data = (object)[
			'dto' => false
		];

		if ( $id = (int)$id) {
			$dao = new dao\ledger;
			$this->data->dto = $dao->getByID( $id);

		}

		$this->render([
			'title' => $this->title = 'create / edit account',
			'primary' => 'edit',
			'secondary' => 'index'

		]);

	}

	public function trial() {
		$dao = new dao\ledger;
		$this->data = (object)[
			'dtoSet' => $dao->trialBalance()
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = 'trial balance',
			'primary' => 'trial-balance',
			'secondary' => 'index']);

	}

	public function balanceSheet() {
		$end = $this->getParam( 'end', date( 'Y-m-d'));
		$dao = new dao\ledger;
		$this->data = (object)[
			'end' => $end,
			'dtoSet' => $dao->balanceSheet( $end)
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = sprintf( 'balance sheet as at : %s', strings::asLocalDate( $end)),
			'primary' => ['end', 'balance-sheet'],
			'secondary' => 'index']);

	}

	public function trading() {
		$start = $this->getParam( 'start', sys::firstDayThisYear());
		$end = $this->getParam( 'end', date( 'Y-m-d'));
		$dao = new dao\ledger;
		$this->data = (object)[
			'start' => $start,
			'end' => $end,
			'dtoSet' => $dao->trading( $start, $end)
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = sprintf( 'trading statement : %s - %s', strings::asLocalDate( $start),  strings::asLocalDate( $end)),
			'primary' => ['start-end', 'trading-statement'],
			'secondary' => 'index']);

	}

	protected function _index() {
		$this->render([
			'title' => $this->title = 'ledger',
			'primary' => 'blank',
			'secondary' => 'index'

		]);

	}

	function index() {
		$this->isPost() ?
      $this->postHandler() :
      $this->_index();

	}

}
