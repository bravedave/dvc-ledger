<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class ledger extends _dao {
	protected $_db_name = 'ledger';

	function getByCode( $code) {
		if ( $res = $this->Result( sprintf( "SELECT * FROM ledger WHERE gl_code = '%s'", $this->escape( $code))))
			return ( $res->dto());

		return ( FALSE);

	}

	function trialBalance() {
		$_sql = 'SELECT *
			FROM ledger l
			LEFT JOIN (
				SELECT glt_code, SUM( glt_value) glt_value
				FROM transactions
				GROUP BY glt_code
				) t
				ON t.glt_code = l.gl_code';
		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

}
