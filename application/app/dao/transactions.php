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

	function getGSTRange( $start, $end) {
		$sql = sprintf( "SELECT
				t.*,
				l.gl_description,
				l.gl_type
			FROM
				`transactions` t
				LEFT JOIN
					`ledger` l ON l.gl_code = t.glt_code
			WHERE
				glt_date BETWEEN '%s' AND '%s' AND glt_gst <> 0
			ORDER BY
				gl_type ASC, glt_code ASC, glt_date DESC", $start, $end);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( false);

	}

	function getRange( $start, $end) {
		$sql = sprintf( "SELECT
				t.*,
				l.gl_description
			FROM
				`transactions` t
				LEFT JOIN
					`ledger` l ON l.gl_code = t.glt_code
			WHERE
				glt_date BETWEEN '%s' AND '%s'
			ORDER BY
				glt_code ASC, glt_date DESC", $start, $end);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( false);

	}

}
