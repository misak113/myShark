
/**
 * MyShark framework for myShark system
 * @autor Michael Žabka
 * @created 14. 12. 2011
 */

function MyShark ($) {
	var myshark = this;
	
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
	
	
	myshark.windows.hideAll();
	$(document).ready(myshark.windows.showAll);
	
	
})(jQuery);
