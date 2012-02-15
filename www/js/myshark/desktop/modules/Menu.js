/**
 * Obohatí o menu module
 * 
 */

(function ($) {
    var myshark = $.myshark;
	
    var menu = new function () {
	var menu = this;
	
	this.SUB_MENU_TYPE_NORMAL = 'normal';
	this.SUB_MENU_TYPE_ROLL_DOWN = 'roll_down';
	this.SUB_MENU_TYPE_FADE_IN = 'fade_in';
	
	this.restart = function () {
	    this.loadSubMenuTypes();
	    this.bindMenuIcons();
	}
	
	this.start = function () {
	    this.loadSubMenuTypes();
	    this.bindMenuIcons();
	}
	
	this.loadSubMenuTypes = function () {
	    $('.Menu .item').each(function () {
		var item = $(this);
		var subMenu = item.find('.items');
		var type = item.attr('data-myshark-sub_menu_type');
		switch (type) {
		    case menu.SUB_MENU_TYPE_FADE_IN:
			menu.loadMenuFadeIn(item, subMenu);
			break;
		}
	    });
	}
	
	this.loadMenuFadeIn = function (item, subMenu) {
	    subMenu.css('opacity', 0).hide();
	    subMenu.addClass('fade-in');
	    item.unbind('mouseover').bind('mouseover', function (ev) {
		subMenu.stop(true).css('display', 'block').animate({
		    opacity: 1
		}, 'fast');
	    }).unbind('mouseout').bind('mouseout', function (ev) {
		subMenu.stop(true).animate({
		    opacity: 0
		}, 'fast', function () {
		    subMenu.css('display', 'none');
		});
	    });
	}
	
	
	this.bindMenuIcons = function () {
	    $('.Menu .edit_menu').unbind('click').click(function (ev) {
		ev.preventDefault();
		var id = $(this).attr('data-myshark-param');
		var icons = $('#content-'+id+' .Menu .items .icon-href');
		if (icons.css('display') == 'inline') {
		    icons.fadeOut('fast');
		} else {
		    icons.fadeIn('fast');
		}
	    });
	}
		
    };
	
    // Před "načtením" stránky
    myshark.modules.menu = menu;
	
    // Po "načtením" stránky
    $(document).ready(function () {
	menu.start();
    });
	
})(jQuery);
