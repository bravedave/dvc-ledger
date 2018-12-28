<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Description:
		Global configuration file
	*/
namespace dvc;

abstract class config extends _config {

	const gl_bank = 'bank';
	const gl_gst = 'gst';

	static $DB_TYPE = 'sqlite';
	static $DATE_FORMAT = 'd-M-y';

	static $PAGE_TEMPLATE = '\page';

	static $WEBNAME = 'My Accounts';

	const use_inline_logon = true;

}
