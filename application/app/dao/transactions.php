<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class transactions extends _dao {
	protected $_db_name = 'transactions';

	function getRecent() {
		if ( $res = $this->getAll())
			return ( $res->dtoSet());

		//~ if ( $res = $this->Result( sprintf( "SELECT * FROM ledger WHERE gl_code = '%s'", $this->escape( $code))))
			//~ return ( $res->dto());

		return ( FALSE);

	}

}
