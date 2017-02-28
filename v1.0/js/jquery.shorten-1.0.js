/*
	jQuery shorten v1.0
	
		Written by:		Wouter Beeftink
		E-mail:			wouter@footsteps.nl
		Last edited:	26 january 2009
	
	Syntax:
	
		$(selector).shorten([options]);
		
	Example:
	
		$('h2, h3').shorten();
		
		$('div.container div.element h2').shorten({
			length : 150										  
		});
		
		$('#myObject').shorten({
			closure : '- the end.'
		});
	
	Options:
	
		length:			The number at which the text cut off.
						The default value is 200.
					
		closure:		The string that will be appended at the end of the text.
						HTML entities are permitted.
						The default value is &hellip; ("...").
*/
(function($) {
	$.fn.shorten = function(options) {
		if(this.length) {
			var options = $.extend({
				length : 200,
				closure : '&hellip;'
			}, options);
			var length = options.length;
			var closure = options.closure;
			return this.each(function() {
				if($(this).text().length > length) {
					var value = $(this).text().slice(0, length);
					if(value.substring(length - 1, length) == ' ')
						value = value.substring(0, length - 1);
					if(closure)
						value += closure;
				};
				$(this).html(value);
			});
		};
	};
})(jQuery);