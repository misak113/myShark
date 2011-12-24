/**
 * Obohatí o animované přechody mezi stránkami pomocí AJAX
 * 
 */

(function ($) {
	var myshark = $.myshark;
	
	var animate = new function () {
		var anim = this;
		
		this.start = function () {
			anim.observeAnchors();
		}
		
		this.observeAnchors = function () {
			$('a').unbind('click', anim.anchorClicked);
			$('a').click(anim.anchorClicked);
		}
		
		this.anchorClicked = function (ev) {
			ev.preventDefault();
			var path = myshark.url.getActualPath();
			myshark.url.setHashmark(path);
		}
		
	};
	myshark.animate = animate;
	
	$(document).ready(animate.start);
	
})(jQuery);
