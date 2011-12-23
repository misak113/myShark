
/**
 * MyShark framework for myShark system
 * @autor Michael Žabka
 * @created 14. 12. 2011
 */

function MyShark ($) {
	
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
	
}


(function ($) {
	
	var myshark = new MyShark($);
	$.myshark = myshark;
	
	
	myshark.windows.hideAll();
	$(document).ready(myshark.windows.showAll);
	
	
})(jQuery);
