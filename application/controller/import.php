<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class import extends Controller {
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

			print 'wrote';

			$csv = new ParseCsv\Csv();	# create new parseCSV object.
			$csv->auto($path . 'p');	# Parse '_books.csv' using automatic delimiter detection...
			sys::dump($csv->data);

		};

		//~ 		$arr = explode( PHP_EOL, $infile);




		//~ 		sys::dump( array($infile ));





	}

}
