<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

abstract class balsheet {
	protected static $_types = [
		'a' => 'assets',
		'b' => 'liability',
		'c' => 'equity'
	];

	static function type( $c) {
		if ( array_key_exists( $c, self::$_types))
			return ( self::$_types[ $c]);

		return ( false);

	}

}
