/*
* jQuery liActualSize v 1.0
* http://mmmagnit.ks.ua
*
* Copyright 2012, Linnik Yura
* Free to use
* 
* Augest 2012
*/
/*
width = $('element').actual('width')
height = $('element').actual('height')
outerWidth = $('element').actual('outerWidth')
outerHeight = $('element').actual('outerHeight')
innerWidth = $('element').actual('innerWidth')
innerHeight = $('element').actual('innerHeight')
*/
(function ($) {
  $.fn.actual = function () {
    if (arguments.length && typeof arguments[0] == 'string') {
      var dim = arguments[0];
      if (this.is(':visible')) return this[dim]();
      var clone = $(this).clone().css({
        position: 'absolute',
        top: '-9999px',
        visibility: 'hidden'
      }).appendTo('body');
      var s = clone[dim]();
      clone.remove();
	 
      return s;
    }
    return undefined;
  };
}(jQuery));