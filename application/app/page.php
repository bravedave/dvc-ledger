<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class page extends dvc\pages\bootstrap4 {
	static $addScripts = true;

	function __construct( $title = '' ) {
		self::$momentJS = self::$addScripts;

		parent::__construct( $title );

		if ( !self::$addScripts)
			return;

		$this->css[] = sprintf( '<link type="text/css" rel="stylesheet" media="all" href="%s" />', url::tostring('css/jquery-ui.min.css'));
		$this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/jquery-ui.min.js'));
		// $this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/moment.min.js'));
		$this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('script'));

	}

}
