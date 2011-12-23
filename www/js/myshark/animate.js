/**
 * Obohatí o animované přechody mezi stránkami pomocí AJAX
 * 
 */

(function ($) {
	var myshark = $.myshark;
	
	var animate = new function ($) {
		
		this.test = function () {
			alert('test animate');
		}
		
	};
	myshark.animate = animate;
	
	$(document).ready(animate.test);
	
})(jQuery);
