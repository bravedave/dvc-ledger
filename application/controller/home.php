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
		if ( $this->isPost())
			$this->_authorize();
		else
			parent::authorize();

	}

	protected function postHandler() {
		$action = $this->getPost( 'action');
		if ( $action == '-system-logon-') {
			/**
			it might not be firstRun and it might not be lockdown
			but if a page requiring authentication it will request
			it through the home page, so process it ..*/
			$this->_authorize();
			return;

		}

	}

	function __construct( $rootPath) {
		$this->firstRun = sys::firstRun();

		if ( $this->firstRun)
			$this->RequireValidation = FALSE;
		else
			$this->RequireValidation = \sys::lockdown();

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
			$p = new page( $this->title = sys::name());
			$p
				->header()
				->title()
				->primary();

				$this->load( 'readme');

			$p->secondary();

				$this->load('main-index');

		}

	}

	public function script() {
		//~ $debug = FALSE;
		$debug = TRUE;

		Response::javascript_headers();

		ob_start();

		$jsFilePath = [$this->rootPath, 'app', 'views', 'js', '*.js'];
		$jsFiles = implode( DIRECTORY_SEPARATOR, $jsFilePath);
		//~ if ( $debug) \sys::logger( sprintf( ' reading :: %s', $jsFiles));
		$gi = new GlobIterator( $jsFiles, FilesystemIterator::KEY_AS_FILENAME);

		$_files = [];
		foreach ($gi as $key => $item) {
			if ( $key == 'primo.js')
				array_unshift( $_files, $item->getRealPath());
			else
				$_files[] = $item->getRealPath();

		}

		foreach ( $_files as $_) {
			if ( $debug) \sys::logger( sprintf( ' reading :: %s', $_));
			include_once $_;
			print PHP_EOL;

		}
		$out = ob_get_contents();
		ob_end_clean();

		if ( $debug || $this->Request->ClientIsLocal()) {
			if ( $debug) \sys::logger( sprintf( ' not minifying jsCMS :: %s', $this->timer->elapsed()));
			print $out;

		}
		else {
			if ( $debug) \sys::logger( sprintf( 'jsCMS :: %s', $this->timer->elapsed()));

			$minifier = new MatthiasMullie\Minify\JS;
			$minifier->add( $out);
			$minified =  $minifier->minify();

			if ( $debug) \sys::logger( sprintf( 'jsCMS :: minified :: %s', $this->timer->elapsed()));

			print $minified;

			if ( $debug) \sys::logger( sprintf( 'jsCMS :: %s', $this->timer->elapsed()));

		}

	}

	public function dbinfo() {
		$p = new page('dbinfo');
			$p
			->header()
			->title()
			->primary();

			$dbinfo = new dao\dbinfo;
			$dbinfo->dump();

		$p->secondary();
			$this->load('main-index');

	}

	//~ public function info() {
		//~ phpinfo();

	//~ }

}
