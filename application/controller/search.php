<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class search extends Controller {
	function index() {
		$this->ledger();

	}

	function ledger() {
		$t = $this->getParam( 'term');
		if ( $t) {
			$_sql = sprintf( "SELECT
					gl_code, gl_description, gl_description label, gl_code value
				FROM
					ledger
				WHERE
					gl_code LIKE '%s%%'
					OR gl_description LIKE '%s%%'
				ORDER BY
					gl_description
				LIMIT 10",
					$this->db->escape( $t),
					$this->db->escape( $t)
					);
			if ( $res = $this->db->Result( $_sql)) {
				new \Json( $res->dtoSet());

			}
			else {
				new \Json('');

			}

		}
		else {
			new \Json('');

		}

	}

}