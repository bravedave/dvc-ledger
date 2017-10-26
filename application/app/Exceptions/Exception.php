<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

NameSpace Exceptions;

class Exception extends \dvc\Exception {
	protected $_ledger = FALSE;

	public function __construct($message = null, $code = 0, Exception $previous = null) {

		if ( !$message && $this->_ledger)
			$message = $this->_ledger;

		// make sure everything is assigned properly
		parent::__construct( $message, $code, $previous);

	}

}
