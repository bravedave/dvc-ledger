<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace dao;

class ledger extends _dao {
	protected $_db_name = 'ledger';

	function getByCode( $code) {
		if ( $res = $this->Result( sprintf( "SELECT * FROM ledger WHERE gl_code = '%s'", $this->escape( $code))))
			return ( $res->dto());

		return ( false);

	}

	static function ledgerType( bool $trading, string $section) {
		if ( $trading) {
			if ( 'a' == $section) return 'income';
			if ( 'b' == $section) return 'cost of sales';
			if ( 'c' == $section) return 'expenses';

		}
		else {
			if ( 'a' == $section) return 'asset';
			if ( 'b' == $section) return 'liability';
			if ( 'c' == $section) return 'equity';

		}

		return 'unknown';

	}

	function trialBalance() {
		$_sql = 'CREATE TEMPORARY TABLE _t (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				gl_code TEXT,
				gl_description TEXT,
				gl_trading INTEGER,
				gl_type TEXT,
				glt_value REAL)';

		$this->Q( $_sql);

		$_sql = 'INSERT INTO  _t (gl_code, gl_description, gl_trading, gl_type, glt_value)
			SELECT
				gl_code, gl_description, gl_trading, gl_type, glt_value
			FROM ledger l
				LEFT JOIN (
					SELECT
						glt_code, SUM( glt_value) glt_value
					FROM
						transactions
					GROUP BY
						glt_code
					) t
					ON t.glt_code = l.gl_code
			ORDER BY
				gl_trading ASC, gl_type ASC';
		$this->Q( $_sql);

		$_sql = 'SELECT SUM( glt_gst) gst FROM transactions';

		if ( $res = $this->Result( $_sql)) {
			if ( $dto = $res->dto()) {
				$a = [ 'glt_value' => $dto->gst];
				if ( $res = $this->Result( 'SELECT id, glt_value FROM _t WHERE gl_code = "gst"')) {
					if ( $dto = $res->dto()) {
						$a['glt_value'] += $dto->glt_value;
						$this->db->Update( '_t', $a, 'WHERE id = ' . (int)$dto->id);

					}
					else {
						$a['gl_code'] = 'gst';
						$a['gl_description'] = 'GST Payable';
						$a['gl_type'] = 'c';

						$this->db->Insert( '_t', $a);

					}

				}

			}

		}

		if ( $res = $this->Result( 'SELECT * FROM _t ORDER BY gl_type'))
			return ( $res->dtoSet());
		//~ if ( $res = $this->Result( $_sql))
			//~ return ( $res->dtoSet());

		return ( FALSE);

	}

	function trading( $start, $end) {
		$_sql = sprintf( "SELECT
			l.gl_code, l.gl_description, l.gl_type, SUM( t.glt_value) glt_value
		FROM transactions t
			LEFT JOIN
				ledger l ON l.gl_code = t.glt_code
		WHERE
			l.gl_trading = 1 AND t.glt_date BETWEEN '%s' AND '%s'
		GROUP BY
			t.glt_code
		ORDER BY
			l.gl_type", $start, $end);;

		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( false);

	}

	function balanceSheet( $end) {
		$_sql = 'CREATE TEMPORARY TABLE _t (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				gl_code TEXT,
				gl_description TEXT,
				gl_trading INTEGER,
				gl_type TEXT,
				glt_value REAL)';

		$this->Q( $_sql);

		$_sql = sprintf( "INSERT INTO  _t (gl_code, gl_description, gl_trading, gl_type, glt_value)
			SELECT
				gl_code, gl_description, gl_trading, gl_type, glt_value
			FROM ledger l
				LEFT JOIN (
					SELECT glt_code, SUM( glt_value) glt_value
					FROM transactions
					WHERE glt_date <= '%s'
					GROUP BY glt_code) t
				ON t.glt_code = l.gl_code
			WHERE
				gl_trading = 0
			ORDER BY gl_trading ASC, gl_type ASC", $end);

		$this->Q( $_sql);

		$_sql = sprintf( "SELECT SUM( glt_gst) gst FROM transactions WHERE glt_date <= '%s'", $end);

		if ( $res = $this->Result( $_sql)) {
			if ( $dto = $res->dto()) {
				$a = [ 'glt_value' => $dto->gst];
				if ( $res = $this->Result( 'SELECT id, glt_value FROM _t WHERE gl_code = "gst"')) {
					if ( $dto = $res->dto()) {
						$a['glt_value'] += $dto->glt_value;
						$this->db->Update( '_t', $a, 'WHERE id = ' . (int)$dto->id);

					}
					else {
						$a['gl_code'] = 'gst';
						$a['gl_description'] = 'GST Payable';
						$a['gl_type'] = 'c';

						$this->db->Insert( '_t', $a);

					}

				}

			}

		}

		$_sql = sprintf( "SELECT SUM( glt_value) glt_value
				FROM transactions
				LEFT JOIN ledger ON gl_code = glt_code
				WHERE
					glt_date <= '%s'
					AND ledger.gl_trading = 1", $end);

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

		return ( false);

	}

}
