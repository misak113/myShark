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
			anim.observeHashmark();
		}
		
		this.observeAnchors = function () {
			$('a').unbind('click', anim.anchorClicked);
			$('a').click(anim.anchorClicked);
		}
		
		this.anchorClicked = function (ev) {
			var path = myshark.url.getPath($(this).attr('href')); 
			if (!path.match(/^https?:\/\//)) {
				ev.preventDefault();
				myshark.url.setHashmark(path);
			} else {
				$(this).attr('target', '_blank')
			}
		}
		
		this.observeHashmark = function () {
			$(window).bind("hashchange", anim.hashmarkChanged);
		}
		
		this.hashmarkChanged = function () {
			var hash = $(location).attr('hash');
			hash = hash.replace('#', '');
			// @todo AJAX na adresu
		}
		
	};
	
	// Před "načtením" stránky
	myshark.animate = animate;
	myshark.redirectHashmark = false;
	
	// Po "načtením" stránky
	$(document).ready(function () {
		animate.start();
	});
	
})(jQuery);
