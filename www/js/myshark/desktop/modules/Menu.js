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
	    menu.loadSubMenuTypes();
	    menu.bindMenuIcons();
	    menu.sortableMenuItems();
	    menu.editableMenuItems();
	}
	
	this.start = function () {
	    menu.loadSubMenuTypes();
	    menu.bindMenuIcons();
	    menu.sortableMenuItems();
	    menu.editableMenuItems();
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
		var id = $.parseJSON($(this).attr('data-myshark-params'));
		var icons = $('#content-'+id+' .Menu .items .icon-href');
		if (icons.css('display') == 'inline') {
		    icons.animate({
			opacity: 0
		    }, 'fast', function () {
			icons.css('display', 'none');
		    });
		} else {
		    icons.css('display', 'inline').animate({
			opacity: 1
		    }, 'fast');
		}
	    });
	}
	
	this.sortableMenuItems = function () {
	    $('.Menu ul.items').sortable({
		update: function (ev, ui) {
		    var itemsEl = $(this);
		    var items = itemsEl.children('li.item');
		    var itemsIds = [];
		    _.each(items, function (item) {
			var id = $.parseJSON($(item).attr('data-myshark-params')).id_item;
			itemsIds.push(id);
		    });
		    menu.updateMenuItemsOrder(itemsIds, function () {
			itemsEl.sortable('cancel');
		    });
		}
	    }).sortable('disable');
	    $('.move_menu_item').bind('mousedown', function (ev) {
		var id = $.parseJSON($(this).attr('data-myshark-params'));
		$('#Menu-item-'+id).parent('ul.items').sortable('enable');
	    }).bind('mouseout', function (ev) {
		var id = $.parseJSON($(this).attr('data-myshark-params'));
		$('#Menu-item-'+id).parent('ul.items').sortable('disable');
	    });
	}
	
	this.updateMenuItemsOrder = function (itemsIds, errorCb) {
	    myshark.loader.post({
		module: 'Menu',
		method: 'sort',
		itemsIds: itemsIds
	    }, function (resp) {
		// Povedlo se
		myshark.windows.infoFlash(_t('Položka menu byla úspěšně přesunuta'));
	    }, function (resp) {
		// nastala chyba
		errorCb();
	    });
	}

	this.editableMenuItems = function () {
	    $('.edit_menu_item').bind('click', menu.showEditWindow);
	}

	this.showEditWindow = function (ev) {
	    var menuItem = $(this).parent('.item');
	    var id_item = $.parseJSON(menuItem.attr('data-myshark-params')).id_item;
	    var editWindow = $('#Menu-window-edit').clone();
	    editWindow.attr('id', 'Menu-window-edit-'+id_item);

	    var name = $.parseJSON(menuItem.attr('data-myshark-params')).name;
	    editWindow.find('[name="name"]').val(name);

	    myshark.windows.window(editWindow);
	}
		
    };
	
    // Před "načtením" stránky
    myshark.modules.menu = menu;
	
    // Po "načtením" stránky
    $(document).ready(function () {
	menu.start();
    });
	
})(jQuery);
