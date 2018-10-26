<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Descripton:
		Class for handling general transactions including
		- reporting
		- payments
		- journals

	*/
class transactions extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');
		if ( $action == 'save transaction') {
			//~ sys::dump( $_POST);

			$a = [
				'glt_timestamp' => db::dbTimeStamp(),
				'glt_type' => $this->getPost('glt_type'),
				'glt_date' => $this->getPost('glt_date'),
				'glt_refer' => $this->getPost('glt_refer')];

			$dao = new dao\transactions;
			if ( 'payment' === $a['glt_type']) {
				$a['glt_code'] = $this->getPost('h_glt_code');
		    $a['glt_value'] = (float)$this->getPost('h_glt_value') * -1;
		    // $a['glt_gst'] = $this->getPost('h_glt_gst');
		    $a['glt_comment'] = $this->getPost('h_glt_comment');
				$dao->Insert( $a);

				// sys::dump( $_POST);

			}
			$codes = $this->getPost( 'glt_code');
			$comments = $this->getPost( 'glt_comment');
			$values = $this->getPost( 'glt_value');
			$gsts = $this->getPost( 'glt_gst');
			$jnl = [];
			if ( count( $codes) == count( $comments) && count( $codes) == count( $values)) {
				for ( $i=0; $i < count( $codes); $i++) {
					$a['glt_code'] = $codes[$i];
					$a['glt_comment'] = $comments[$i];
					$a['glt_value'] = $values[$i];
					$a['glt_gst'] = $gsts[$i];

					$jnl[] = (object)$a;

					$dao->Insert( $a);

				}

			}
			else {
				throw new \Exceptions\InvalidJournalStructure;

			}

			//~ sys::dump( $jnl);
			Response::redirect( 'transactions', 'posted transaction');


		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	function edit( $id = 0) {
		$this->data = (object)[
			'glt_date' => date( 'Y-m-d'),
			'glt_refer' => '',
			'lines' => [(object)[
				'glt_code' => 'bank',
				'glt_comment' => 'Bunnings Hardware',
				'glt_value' => -110,
				'glt_gst' => 0
			],
			(object)[
				'glt_code' => 'expenses',
				'glt_comment' => 'Hammer & Chisel',
				'glt_value' => 100,
				'glt_gst' => 10
			]]
		];

		//~ if ( $id = (int)$id) {
			//~ $dao = new dao\ledger;
			//~ $this->data->dto = $dao->getByID( $id);

		//~ }

		$this->render([
			'title' => $this->title = 'create / edit transaction',
			'primary' => 'edit',
			'secondary' => 'index']);

	}

	public function pay() {
		$this->data = (object)[
			'glt_date' => date( 'Y-m-d'),
			'glt_code' => 'bank',
			'glt_refer' => '',
			'glt_value' => 110,
			'glt_gst' => 10,
			'glt_comment' => 'Bunnings Hardware',
			'lines' => [(object)[
				'glt_code' => 'expenses',
				'glt_comment' => 'Hammer & Chisel',
				'glt_value' => 100,
				'glt_gst' => 10
			]]
		];

		// $this->modal([
		// 	'title' => $this->title = 'pay',
		// 	'load' => 'pay']);

		$this->render([
			'title' => $this->title = 'pay',
			'primary' => 'pay',
			'secondary' => 'index']);

	}

	protected function _index() {
		$dao = new dao\transactions;
		$this->data = (object)[
			'dtoSet' => $dao->getRecent()
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = 'transactions',
			'primary' => 'report',
			'secondary' => 'index']);

	}

	function index() {
		$this->isPost() ?
      $this->postHandler() :
      $this->_index();

	}

}
