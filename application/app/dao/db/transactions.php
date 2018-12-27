<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  Description
    Description file for transaction table

	*/
namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'transactions' );
$dbc->defineField( 'glt_code', 'text');
$dbc->defineField( 'glt_type', 'text');
$dbc->defineField( 'glt_date', 'text');
$dbc->defineField( 'glt_refer', 'text');
$dbc->defineField( 'glt_timestamp', 'text');
$dbc->defineField( 'glt_comment', 'text');
$dbc->defineField( 'glt_value', 'float');
$dbc->defineField( 'glt_gst', 'float');
$dbc->defineField( 'glt_gst_remit', 'int');
$dbc->check();
