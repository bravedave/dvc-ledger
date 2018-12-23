<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
abstract class sys extends dvc\sys {
	static function firstDayThisYear() {
		if ( date( 'Y-m-d') > date( 'Y-06-30')) {
			return date( 'Y-07-01');

		}
		else {
			return ( sprintf( '%s-07-01', date('Y')-1));

		}

	}

	static function name() {
		$dao = new dao\settings;
		return ( $dao->getName());

	}

	static function lockdown() {
		$dao = new dao\settings;
		return ( $dao->lockdown());

	}

	static function firstRun() {
		$dao = new dao\settings;
		return ( $dao->firstRun());

	}

}
