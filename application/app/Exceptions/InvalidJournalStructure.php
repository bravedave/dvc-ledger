<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace Exceptions;

class InvalidJournalStructure extends Exception {
	protected $_ledger = 'The Journal structure is invalid';

}
