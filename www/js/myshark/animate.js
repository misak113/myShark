/**
 * Obohatí o animované přechody mezi stránkami pomocí AJAX
 * 
 */

(function ($) {
    var myshark = $.myshark;
	
    var animate = new function () {
	var anim = this;
		
	this.restart = function () {
	    anim.observeAnchors();
	    anim.observeHashmark();
	}
	this.start = function () {
	    anim.observeAnchors();
	    anim.observeHashmark();
	    anim.hashmarkChanged();
	}
		
	this.observeAnchors = function () {
	    $('a').unbind('click', anim.anchorClicked)
	    .click(anim.anchorClicked);
	}
		
	this.anchorClicked = function (ev) {
	    var path = myshark.url.getPath($(this).attr('href')); 
	    if (!path.match(/^https?:\/\//)) {
		ev.preventDefault();
		if (path.indexOf('#') == -1) {
		    myshark.url.setHashmark(path);
		}
	    } else {
		$(this).attr('target', '_blank')
	    }
	}
		
	this.observeHashmark = function () {
	    $(window).unbind('hashchange', anim.hashmarkChanged).bind("hashchange", anim.hashmarkChanged);
	}
		
	this.hashmarkChanged = function () {
	    var hash = $(location).attr('hash');
	    hash = hash.replace('#', '');
	    myshark.loader.load(hash, anim.refreshPage);
	}
	this.refreshPage = function (resp) {
	    var html = $(resp);
	    var body = html.filter('#body');
	    
	    // Přidání head součástí
	    
	    
	    // Překresluje snippety
	    var snippetSelector = '[id*="snippet-"]';
	    var ids = myshark.dom.getDifferentIds(snippetSelector, $('#body'), body);
	    animate.changeElementsHtmls(ids, $('#body'), body, function () {
		anim.restart();
		myshark.windows.showAll();
	    });
	}
	
	this.changeElementsHtmls = function (ids, oldMain, newMain, cb) {
	    $.each(ids, function (i, id) {
		//oldMain.find('#'+id).html(newMain.find('#'+id).html());
		var oldEl = oldMain.find('#'+id);
		var newEl = newMain.find('#'+id);
		oldEl.fadeOut('fast', 
		function () {
		    oldEl.html(newEl.html());
		    oldEl.fadeIn('fast', cb);
		});
	    });
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
