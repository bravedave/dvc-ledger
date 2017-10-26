<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

abstract class trading {
	protected static $_types = [
		'a' => 'income',
		'b' => 'cost of sales',
		'c' => 'expenses'
	];

	static function type( $c) {
		if ( array_key_exists( $c, self::$_types))
			return ( self::$_types[ $c]);

		return ( FALSE);

	}

}