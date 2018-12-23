<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class settings extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');

		if ( $action == 'update') {
			$a = [
				'name' => $this->getPost( 'name'),
				'lockdown' => (int)$this->getPost('lockdown')];

			$dao = new dao\settings;
			$dao->UpdateByID( $a, 1);

			Response::redirect( url::$URL, 'updated settings');

		}
		else {
			throw new dvc\Exceptions\InvalidPostAction;

		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	protected function _index() {
		$dao = new dao\settings;
		if ( $res = $dao->getAll()) {
			$this->data = $res->dto();

			//~ sys::dump( $this->data);

			$this->render([
				'title' => $this->title = 'Settings',
				'primary' => 'settings',
				'secondary' => 'main-index'

			]);

		}
		else {
			throw new \Exception( 'missing system settings');

		}

	}

	function index() {
		$this->isPost() ?
      $this->postHandler() :
      $this->_index();

	}

}
