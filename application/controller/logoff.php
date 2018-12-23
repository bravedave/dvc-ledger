<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class logoff extends Controller {
	public $RequireValidation = false;
	public $CheckOffline = false;

	function index() {
		\session::destroy();
		Response::redirect();

	}

}
