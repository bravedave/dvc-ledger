<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class home extends Controller {
	protected $firstRun = false;

	protected function _authorize() {
		$action = $this->getPost( 'action');
		if ( $action == '-system-logon-') {
			if ( $u = $this->getPost( 'u')) {
				if ( $p = $this->getPost( 'p')) {
					$dao = new \dao\users;
					if ( $dto = $dao->validate( $u, $p))
						\Json::ack( $action);
					else
						\Json::nak( $action);
					die;

				}

			}

		}
		throw new dvc\Exceptions\InvalidPostAction;

	}

	protected function authorize() {
		if ( $this->isPost()) {
			$this->_authorize();

		}
		else {
			parent::authorize();

		}

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');
		if ( $action == '-system-logon-') {
			/**
			* it might not be firstRun and it might not be lockdown
			* but if a page requiring authentication it will request
			* it through the home page, so process it ..*/
			$this->_authorize();
			return;

		}

	}

	function __construct( $rootPath) {
		$this->firstRun = sys::firstRun();

		if ( $this->firstRun) {
			$this->RequireValidation = false;

		}
		else {
			$this->RequireValidation = \sys::lockdown();

		}

		page::$addScripts = ( !$this->RequireValidation || $this->authorised);

		parent::__construct( $rootPath);

	}

	public function _index( $data = '' ) {
		$this->render([
			'title' => $this->title = sys::name(),
			'primary' => 'readme',
			'secondary' => ['ledger/index', 'transactions/index', 'main-index']

		]);

	}

	public function dbinfo() {
		$this->render([
			'title' => 'dbinfo',
			'primary' => 'db-info',
			'secondary' => 'main-index'

		]);

	}

	function index() {
		if ( $this->firstRun) {
			$this->dbinfo();

		}
		else {
			$this->isPost() ?
				$this->postHandler() :
				$this->_index();

		}

	}

	public function script() {
		$jsFilePath = [$this->rootPath, 'app', 'views', 'js', '*.js'];
		jslib::viewjs([
			'libName' => 'ledger',
			'leadKey' => 'primo.js',
			'jsFiles' => implode( DIRECTORY_SEPARATOR, $jsFilePath),
			'libFile' => config::tempdir()  . 'ledger.js'

		]);

	}

	function info() {
		/**
		 * default setting
		 *
		 * in case you forget to disable this on a production server
		 * - only running on localhost
		 */

		if ( $this->Request->ServerIsLocal()) {
			$this->render([
				'title' => 'info',
				'primary' => 'info',
				'secondary' => 'main-index'
			]);

		}

	}

}
