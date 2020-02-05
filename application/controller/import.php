<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class import extends Controller {
	protected function posthandler() {
		$action = $this->getPost('action');

		if ( 'gibblegok' == $action) { \Json::ack( $action); }
		else { \Json::nak( $action); }

	}

	protected function _index() {
		if ( $files = qbimport::files()) {
			$this->data = (object)[
				'data' => []

			];

			foreach( $files as $file) {
				sys::logger( $file->path);
				$arr = file( $file->path);

				/** Remove the first 4 line **/
				if (count($arr) > 0) array_shift($arr);
				if (count($arr) > 0) array_shift($arr);
				if (count($arr) > 0) array_shift($arr);
				if (count($arr) > 0) array_shift($arr);

				while ( count($arr) > 0 ) {
					$v = array_pop($arr);
					//~ printf( '<pre>:%s:</pre>', $v);

					if ( $v == '' ) continue;
					if ( preg_match( '@[,]{9}$@', trim( $v))) continue;
					//~ die( $v);

					break;

				}
				$infile = implode($arr);

				$search = ['@[^a-zA-Z0-9\s\p{P}]@'];
				$replace = [''];

				if ( file_put_contents( $file->path . 'p', $infile ) === false ) {
				// if ( file_put_contents( $file->path . 'p', preg_replace( $search, $replace, $infile )) === false ) {
					die( 'fail @ write' );

				}

				$csv = new ParseCsv\Csv;
				$csv->auto( $file->path . 'p');
				$this->data->data[] = $csv->data;
				// sys::dump( $this->data->data, $file->path . 'p');
				// sys::dump( $csv->error_info);

			}

			$this->render([
				'title' => 'Import',
				'primary' => 'import',
				'secondary' =>'main-index'
			]);

		}
		else {
			$this->render([
				'title' => 'Import',
				'primary' => 'not-found',
				'secondary' =>'main-index'
			]);

		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	function qbooks() {
		// so I have a quickbox online file, exported to excel, saved as csv with columns
		// ,Date,Transaction Type,No.,Name,Memo/Description,Split,Amount,Balance,Account


		// die( config::dataPath());

		if ( $path = realpath( sprintf( '%s%sqbooks.csv', config::dataPath(), DIRECTORY_SEPARATOR))) {
			// die( $path);

			// $infile = file_get_contents( $path);
			$arr = file( $path);

			/** Remove the first 4 line **/
			if (count($arr) > 0) array_shift($arr);
			if (count($arr) > 0) array_shift($arr);
			if (count($arr) > 0) array_shift($arr);
			if (count($arr) > 0) array_shift($arr);
			//~ sys::dump( $arr);

			while ( count($arr) > 0 ) {
				$v = array_pop($arr);
				//~ printf( '<pre>:%s:</pre>', $v);

				if ( $v == '' ) continue;
				if ( preg_match( '@[,]{9}$@', trim( $v))) continue;
				//~ die( $v);

				break;

			}

			$infile = implode($arr);

			$search = ['@[^a-zA-Z0-9\s\p{P}]@'];
			$replace = [''];

			if ( file_put_contents( $path . 'p' , preg_replace( $search, $replace, $infile )) === false ) {
				die( 'fail @ write' );

			}

			$csv = new ParseCsv\Csv;	# create new parseCSV object.
			$csv->auto($path . 'p');	# Parse '_books.csv' using automatic delimiter detection...

			$this->data = (object)[
				'journal' => $csv->data

			];

			$this->render([
				'title' => $this->title = sys::name(),
				'primary' => 'report',
				'secondary' => ['ledger/index', 'transactions/index', 'main-index']

			]);



		};

		//~ 		$arr = explode( PHP_EOL, $infile);




		//~ 		sys::dump( array($infile ));





	}

}
