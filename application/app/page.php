<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class page extends dvc\pages\bootstrap {
	function __construct( $title = '' ) {
		self::$BootStrap_Version = '4';
		parent::__construct( $title );

		$this->css[] = sprintf( '<link type="text/css" rel="stylesheet" media="all" href="%s" />', url::tostring('css/jquery-ui.min.css'));
		$this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/jquery-ui.min.js'));
		$this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/moment.min.js'));
		$this->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/custom.js'));

	}

}
