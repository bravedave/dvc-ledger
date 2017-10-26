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
				ON t.glt_code = l.gl_code
			ORDER BY gl_trading ASC, gl_type ASC';
		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	function trading() {
		$_sql = 'SELECT
			gl_code, gl_description, gl_type, glt_value
		FROM transactions
			LEFT JOIN
				ledger ON gl_code = glt_code
		WHERE
			ledger.gl_trading = 1
		ORDER BY
			gl_type';

		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	function balanceSheet() {
		$_sql = 'CREATE TEMPORARY TABLE
			_t AS
		SELECT
			gl_code, gl_description, gl_trading, gl_type, glt_value
		FROM ledger l
			LEFT JOIN (
				SELECT glt_code, SUM( glt_value) glt_value
				FROM transactions
				GROUP BY glt_code) t
			ON t.glt_code = l.gl_code
		WHERE gl_trading = 0
		ORDER BY gl_trading ASC, gl_type ASC';

		$this->Q( $_sql);

		$_sql = 'SELECT SUM( glt_value) glt_value
				FROM transactions
				LEFT JOIN ledger ON gl_code = glt_code
				WHERE ledger.gl_trading = 1';

		if ( $res = $this->Result( $_sql)) {
			if ( $dto = $res->dto()) {
				$a = [
					'gl_code' => 'p/l',
					'gl_description' => 'Retained Profits',
					'gl_type' => 'c',
					'glt_value' => $dto->glt_value];
				$this->db->Insert( '_t', $a);

			}

		}

		if ( $res = $this->Result( 'SELECT * FROM _t ORDER BY gl_type'))
			return ( $res->dtoSet());

		return ( FALSE);

	}

}
