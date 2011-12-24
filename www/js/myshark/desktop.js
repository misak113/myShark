
/**
 * MyShark framework for myShark system
 * @autor Michael Žabka
 * @created 14. 12. 2011
 */

function MyShark ($) {
	var myshark = this;
	
	this.redirectHashmark = true;
	this.baseUrl = null;
	this.animate = null;
	
	this.windows = new function () {
		var win = this;
		
		this.defaultDialogOptions = {
			width: 640,
			height: 480,
			show: 'slide',
			hide: 'explode',
			resizable: false
		};
		
		this.hideAll = function () {
			$('.window').hide();
		}
		
		this.showAll = function () {
			$('.window').dialog(win.defaultDialogOptions);
		}
		
	}
	
	this.url = new function () {
		var url = this;
		
		this.getActualPath = function () {
			var href = $(location).attr('href').split('#');
			href = href[0];
			return url.getPath(href);
		}
		
		this.getPath = function (href) {
			href = href.replace(myshark.baseUrl+'/', '');
			return href;
		}

		this.setHashmark = function (hashmark) {
			$(location).attr('hash', hashmark);
		}
	}
	
}


(function ($) {
	
	var myshark = new MyShark($);
	$.myshark = myshark;
	
	
	// Před "načtením" stránky
	myshark.windows.hideAll();
	
	// Po "načtení" stránky
	$(document).ready(function () {
		// windows
		myshark.windows.showAll();
		
		// redirect hash, if not animate
		if (myshark.redirectHashmark) {
			var hash = $(location).attr('hash');
			hash = hash.replace('#', '');
			if (hash != '') {
				$(location).attr('href', myshark.baseUrl+'/'+hash);
			}
		}
		
	});
	
	
})(jQuery);
