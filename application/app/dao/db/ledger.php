<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'ledger' );
$dbc->defineField( 'gl_code', 'text');
$dbc->defineField( 'gl_description', 'text');
$dbc->defineField( 'gl_trading', 'int');
$dbc->defineField( 'gl_type', 'text');
$dbc->defineField( 'created', 'text');
$dbc->check();

if ( $res = $this->db->Result( 'SELECT count(*) count FROM ledger' )) {
	if ( $dto = $res->dto()) {
		if ( $dto->count < 1 ) {

			/* not all codes are lower case */
			$a = [];
			$a[] = [ 'gl_code' => \config::gl_bank, 'gl_description' => 'Bank Account', 'gl_trading' => 0, 'gl_type' => 'a' ];
			$a[] = [ 'gl_code' => \config::gl_gst, 'gl_description' => 'GST Payable', 'gl_trading' => 0, 'gl_type' => 'b' ];
			$a[] = [ 'gl_code' => 'owner', 'gl_description' => 'Business Owner', 'gl_trading' => 0, 'gl_type' => 'c' ];

			$a[] = [ 'gl_code' => 'sales', 'gl_description' => 'Sales', 'gl_trading' => 1, 'gl_type' => 'a' ];

			$a[] = [ 'gl_code' => 'purchases', 'gl_description' => 'Purchases', 'gl_trading' => 1, 'gl_type' => 'b' ];

			$a[] = [ 'gl_code' => 'travel', 'gl_description' => 'travel', 'gl_trading' => 1, 'gl_type' => 'c' ];
			$a[] = [ 'gl_code' => 'stat', 'gl_description' => 'stationary & postage', 'gl_trading' => 1, 'gl_type' => 'c' ];
			$a[] = [ 'gl_code' => 'expenses', 'gl_description' => 'Expenses', 'gl_trading' => 1, 'gl_type' => 'c' ];

			foreach( $a as $_) {
				$a[ 'created' ] = \db::dbTimeStamp();
				$this->db->Insert( 'ledger', $_ );

			}

			\sys::logger( 'wrote default ledger');

		}

	}

}
