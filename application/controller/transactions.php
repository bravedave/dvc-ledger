<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class transactions extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');
		if ( $action == 'save transaction') {
			$a = [
				'glt_timestamp' => db::dbTimeStamp(),
				'glt_date' => $this->getPost('glt_date'),
				'glt_refer' => $this->getPost('glt_refer')];

			$dao = new dao\transactions;
			$codes = $this->getPost( 'glt_code');
			$comments = $this->getPost( 'glt_comment');
			$values = $this->getPost( 'glt_value');
			if ( count( $codes) == count( $comments) && count( $codes) == count( $values)) {
				for ( $i=0; $i < count( $codes); $i++) {
					$a['glt_code'] = $codes[$i];
					$a['glt_comment'] = $comments[$i];
					$a['glt_value'] = $values[$i];

					$dao->Insert( $a);

				}

			}
			else {
				throw new \Exceptions\InvalidJournalStructure;

			}

			Response::redirect( 'transactions', 'posted transaction');

			//~ sys::dump( $a);
			//~ sys::dump( $_POST);

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
				'glt_comment' => 'tools',
				'glt_value' => 100,
			]]
		];

		//~ if ( $id = (int)$id) {
			//~ $dao = new dao\ledger;
			//~ $this->data->dto = $dao->getByID( $id);

		//~ }

		$p = new page( $this->title = 'created / edit transaction');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('edit');

			$p->secondary();
				$this->load('index');

	}

	protected function _index() {
		$dao = new dao\transactions;
		$this->data = (object)[
			'dtoSet' => $dao->getRecent()
		];

		$p = new page( $this->title = 'transactions');
			$p
				->header()
				->title();

			$p->primary();
				//~ sys::dump( $this->data, NULL, FALSE);
				$this->load('report');

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
