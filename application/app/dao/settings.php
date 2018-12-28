<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace dao;

class settings extends _dao {
	protected $_db_name = 'settings';

	function firstRun() {
		/*
		test if the settings table exists
		if it does - it is not first run
		*/

		if ( $res = $this->Result( "SELECT name FROM sqlite_master WHERE type='table' AND name='settings';")) {
			return ( !$res->dto());

		}

		return ( true);

	}

	function getFirst() {
		if ( $res = $this->Result( "SELECT * FROM settings")) {
			return ( $res->dto());

		}

		return ( false);

	}

	function getName() {
		if ( $dto = $this->getFirst()) {
			return ( $dto->name);

		}

		return ( \config::$WEBNAME);

	}

	function getTransaction() {
		if ( $dto = $this->getFirst()) {
			$dto->transaction++;
			$this->UpdateByID( [ 'transaction' => $dto->transaction], $dto->id);
			return ( $dto->transaction);

		}

		return ( 0);

	}

	function lockdown( $set = null) {
		$lockdown = false;
		if ( $dto = $this->getFirst()) {
			$lockdown = (int)$dto->lockdown;

			if ( !is_null( $set)) {
				$this->Q( sprintf( "UPDATE `settings` SET `lockdown` = %d", (int)$set));

			}

		}

		//~ \sys::logger( sprintf( 'lockdown = %s', ( $lockdown ? 'TRUE' : 'FALSE' )));

		return ( $lockdown);

	}

}
