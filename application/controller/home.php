<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class home extends Controller {
	protected $firstRun = FALSE;

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

	public function index( $data = '' ) {
		if ( $this->isPost()) {
			$this->postHandler();

		}
		elseif ( $this->firstRun) {
			$this->dbinfo();

		}
		else {
			$this->render([
				'title' => $this->title = sys::name(),
				'primary' => 'readme',
				'secondary' => 'main-index'

			]);

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

	public function dbinfo() {
		$this->render([
			'title' => 'dbinfo',
			'primary' => 'db-info',
			'secondary' => 'main-index'

		]);

	}

	//~ public function info() {
		//~ phpinfo();

	//~ }

}
