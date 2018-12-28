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
		if ( $action == 'gst_remit') {
			if ( $id = (int)$this->getPost('id')) {
				$val = (int)$this->getPost('value');
				$a = [
						'glt_gst_remit' => $val

				];

				$dao = new dao\transactions;
				$dao->UpdateByID( $a, $id);

				\Json::ack( sprintf( '%s - %s', $action, $val ? 'on' : 'off'));

			}
			else {
				\Json::nak( $action);

			}

		}
		elseif ( $action == 'save transaction') {
			$format = $this->getPost('format');
			//~ sys::dump( $_POST);

			$dao = new dao\settings;
			$transaction = $dao->getTransaction();

			$a = [
				'glt_timestamp' => db::dbTimeStamp(),
				'glt_type' => $this->getPost('glt_type'),
				'glt_date' => $this->getPost('glt_date'),
				'glt_refer' => $this->getPost('glt_refer'),
				'glt_transaction' => $transaction

			];

			$dao = new dao\transactions;
			if ( 'payment' === $a['glt_type'] || 'receipt' === $a['glt_type']) {
				$a['glt_code'] = $this->getPost('h_glt_code');
				if ( 'payment' === $a['glt_type']) {
					$a['glt_value'] = (float)$this->getPost('h_glt_value') * -1;

				}
				else {
					$a['glt_value'] = (float)$this->getPost('h_glt_value');

				}

				// $a['glt_gst'] = $this->getPost('h_glt_gst');
				$a['glt_comment'] = $this->getPost('h_glt_comment');
				$dao->Insert( $a);

				// sys::dump( $_POST);

			}
			$codes = $this->getPost( 'glt_code');
			$comments = $this->getPost( 'glt_comment');
			$values = $this->getPost( 'glt_value');
			$gsts = $this->getPost( 'glt_gst');
			$gstID = 0;
			$jnl = [];
			if ( count( $codes) == count( $comments) && count( $codes) == count( $values)) {
				for ( $i=0; $i < count( $codes); $i++) {
					$a['glt_code'] = $codes[$i];
					$a['glt_comment'] = $comments[$i];
					if ( 'receipt' === $a['glt_type']) {
						$a['glt_value'] = $values[$i] * -1;
						$a['glt_gst'] = $gsts[$i] * -1;

					}
					else {
						$a['glt_value'] = $values[$i];
						$a['glt_gst'] = $gsts[$i];

					}

					$jnl[] = (object)$a;
					$dao->Insert( $a);

				}

				if ( (bool)$this->getPost('glt_gstpayment')) {
					$dao->flagRemitedGST( $transaction);

				}

			}
			else {
				throw new \Exceptions\InvalidJournalStructure;

			}

			//~ sys::dump( $jnl);
			if ( $format == 'json') {
				\Json::ack( $action);

			}
			else {
				Response::redirect( 'transactions', 'posted transaction');

			}

		}

	}

	public function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	public function edit( $id = 0) {
		$this->data = (object)[
			'glt_date' => date( 'Y-m-d'),
			'glt_refer' => '',
			'lines' => []

		];
		// (object)[
		// 	'glt_code' => \config::gl_bank,
		// 	'glt_comment' => 'Bunnings Hardware',
		// 	'glt_value' => -110,
		// 	'glt_gst' => 0
		//
		// ],
		// (object)[
		// 	'glt_code' => 'expenses',
		// 	'glt_comment' => 'Hammer & Chisel',
		// 	'glt_value' => 100,
		// 	'glt_gst' => 10
		//
		// ]


		$this->render([
			'title' => $this->title = 'create / edit transaction',
			'primary' => 'edit',
			'secondary' => ['index', 'ledger/index']

		]);

	}

	public function gst() {
		$start = $this->getParam( 'start', sys::firstDayThisYear());
		$end = $this->getParam( 'end', date( 'Y-m-d'));
		$dao = new dao\transactions;
		$this->data = (object)[
			'start' => $start,
			'end' => $end,
			'dtoSet' => $dao->getGSTRange( $start, $end)
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = sprintf( 'gst : %s - %s', strings::asLocalDate( $start),  strings::asLocalDate( $end)),
			'primary' => ['start-end', 'report-gst'],
			'secondary' => ['index', 'ledger/index']

		]);

	}

	public function pay() {
		$this->data = (object)[
			'glt_date' => date( 'Y-m-d'),
			'glt_code' => \config::gl_bank,
			'glt_refer' => '',
			'glt_value' => 0,
			'glt_gst' => 0,
			'glt_comment' => '',
			'glt_gstpayment' => false,
			'lines' => [
				(object)[
					'glt_code' => 'expenses',
					'glt_comment' => '',
					'glt_value' => 0,
					'glt_gst' => 0

				]

			]

		];

		$this->modal([
			'title' => $this->title = 'pay',
			'class' => 'modal-full',
			'load' => 'pay'

		]);

	}

	public function paygst() {
		$dao = new dao\transactions;
		if ( $dto = $dao->getGSTRemited()) {

			// $this->data = $dto;

			$this->data = (object)[
				'glt_date' => date( 'Y-m-d'),
				'glt_code' => \config::gl_bank,
				'glt_refer' => 'gst-pay',
				'glt_value' => $dto->totalGST,
				'glt_gst' => 0,
				'glt_comment' => 'GST Installment',
				'glt_gstpayment' => true,
				'lines' => [
					(object)[
						'glt_code' => \config::gl_gst,
						'glt_comment' => 'GST Installment',
						'glt_value' => $dto->totalGST,
						'glt_gst' => 0

					]

				]

			];

			$this->modal([
				'title' => $this->title = 'pay',
				'class' => 'modal-full',
				'load' => 'pay'

			]);

		}

	}

	public function receipt() {
		$this->data = (object)[
			'glt_date' => date( 'Y-m-d'),
			'glt_code' => \config::gl_bank,
			'glt_refer' => '',
			'glt_value' => 0,
			'glt_gst' => 0,
			'glt_comment' => '',
			'lines' => [
				(object)[
					'glt_code' => 'sales',
					'glt_comment' => '',
					'glt_value' => 0,
					'glt_gst' => 0
				]

			]

		];

		$this->modal([
			'title' => $this->title = 'receipt',
			'class' => 'modal-full',
			'load' => 'receipt'

		]);

	}

	protected function _index() {
		$start = $this->getParam( 'start', sys::firstDayThisYear());
		$end = $this->getParam( 'end', date( 'Y-m-d'));
		$dao = new dao\transactions;
		$this->data = (object)[
			'start' => $start,
			'end' => $end,
			'dtoSet' => $dao->getRange( $start, $end)
		];

		//~ sys::dump( $this->data);

		$this->render([
			'title' => $this->title = sprintf( 'transactions : %s - %s', strings::asLocalDate( $start),  strings::asLocalDate( $end)),
			'primary' => ['start-end', 'report'],
			'secondary' => ['index', 'ledger/index']

		]);

	}

	function index() {
		$this->isPost() ?
      $this->postHandler() :
      $this->_index();

	}

}
