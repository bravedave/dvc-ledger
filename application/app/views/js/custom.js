/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

*/
$.fn.zIndex = function ( z) {
	if ( /number|string/.test( typeof z)) {
		return ( this.css('z-index',z));	// consistent

	}
	else {
		// otherwise the calculated value
		var z = window.document.defaultView.getComputedStyle(this[0]).getPropertyValue('z-index');
		if ( isNaN( z))
			z = 0;

		z = parseInt( z);
		$.each( this.parents(), function( i, el) {
			var _z = window.document.defaultView.getComputedStyle(el).getPropertyValue('z-index');
			if ( !isNaN( _z))
				z += parseInt( _z);

		});
		return z;

	}

};


$.fn.calendarHelper = function() {

	var _me = this;
	var p = _me.parent();

	p.addClass('input-group has-input-group-addon');
	var hc = $('<span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar " /></span>');
	hc.appendTo( p);

	hc.on( 'click', function( e) {
		if ( _me.val() == '')
			_me.val( moment().format('YYYY-MM-DD'));


		if ( !_me.hasClass('hasDatepicker')) {
			_me.css('z-index', _me.zIndex());	// calculated
			_me.datepicker({ "dateFormat": 'yy-mm-dd' });

		}
		_me.focus();

	});

}
