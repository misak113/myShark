
/**
 * MyShark framework for myShark system
 * @autor Michael Žabka
 * @created 14. 12. 2011
 */


(function ($) {
    
    
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
		show: 'highlight',
		hide: 'highlight',
		resizable: false
	    };
		
	    this.hideAll = function () {
		$('#windows .window').hide();
	    }
		
	    this.showAll = function () {
		$('#windows .window').dialog(win.defaultDialogOptions);
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
	    
	    this.redirectByHash = function () {
		// redirect hash, if not animate
		if (myshark.redirectHashmark) {
		    var hash = $(location).attr('hash');
		    hash = hash.replace('#', '');
		    if (hash != '') {
			$(location).attr('href', myshark.baseUrl+'/'+hash);
		    }
		}
	    }
	}
	
	this.loader = new function () {
		
	    this.load = function (url, cb) {
		myshark.loader.showLoading();
		$.ajax({
		    url: myshark.baseUrl+'/'+url,
		    dataType: 'html',
		    method: 'GET',
		    success: function (resp) {
			myshark.loader.hideLoading();
			cb(resp);
		    }
		});
	    }
                
	    this.showLoading = function () {
		$('#loading-box').fadeIn('fast');//css('display', 'block');
	    }
                
	    this.hideLoading = function () {
		$('#loading-box').fadeOut('fast');//css('display', 'none');
	    }
	}
	
	this.dom = new function () {
	    
	    this.getDifferentIds = function (selector, oldMain, newMain) {
		var newEls = newMain.find(selector);
		var oldEls = oldMain.find(selector);
		
		var changes = {};
		changes.toChange = [];
		newEls.each(function () {
		    var newEl = $(this);
		    var id = newEl.attr('id');
		    var oldEl = oldEls.filter('#'+id);
		    
		    if (oldEl.length == 0) {
			var change = newEl.parent(selector);
			changes.toChange.push(change.attr('id'));
		    } else
		    if (oldEl.html() != newEl.html()) {
			changes.toChange.push(id);
		    }
		});
		
		changes.toChangeControlled = changes.toChange;
		$.each(changes.toChange, function (i, id1) {
		    var el1 = $('#'+id1);
		    $.each(changes.toChange, function (j, id2) {
			var el2 = $('#'+id2);
			var child = el1.find('#'+id2);
			if (child.length > 0) {
			    changes.toChangeControlled = myshark.util.removeFromArray(id1, changes.toChangeControlled);
			}
		    });
		});
		
		return changes.toChangeControlled;
	    }
	    
	    
	}
	
	this.util = new function () {
	    this.removeFromArray = function (string, array){
		while($.inArray(string,array)!=-1){
		    array.splice($.inArray(string,array), 1);
		}
		return array;
	    }
	}
	
    }

	
    var myshark = new MyShark($);
    $.myshark = myshark;
	
	
    	
    // Před "načtením" stránky
    myshark.windows.hideAll();
	
    // Po "načtení" stránky
    $(document).ready(function () {
	myshark.url.redirectByHash();
    
	// Po "načtení" celého okna
	$(window).bind('load', function () {
	    // windows
	    myshark.windows.showAll();
	    myshark.loader.hideLoading();
	});
		
    });
	
	
})(jQuery);
