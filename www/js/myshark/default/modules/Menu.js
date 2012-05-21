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
				var id = $.parseJSON($(this).attr('data-kate-params'));
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
				var id = $.parseJSON($(this).attr('data-kate-params'));
				$('#Menu-item-'+id).parent('ul.items').sortable('enable');
			}).bind('mouseout', function (ev) {
				var id = $.parseJSON($(this).attr('data-kate-params'));
				$('#Menu-item-'+id).parent('ul.items').sortable('disable');
			});
		}
	
		this.updateMenuItemsOrder = function (itemsIds, errorCb) {
			myshark.loader.post({
				module: 'Menu',
				method: 'sortItems',
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
			$('.edit_menu_item').unbind('click').bind('click', menu.showEditWindow);
		}

		this.showEditWindow = function (ev) {
			var menuItem = $(this).parent('.item');
			var item = $.parseJSON(menuItem.attr('data-myshark-params'));
			var id_item = item.id_item;
			var editWindow = $('#Menu-window-edit').clone(true);
			editWindow.attr('id', 'Menu-window-edit-'+id_item);

			var name = item.text;
			editWindow.find('[name="name"]').val(name);

			var link = item.link;
			editWindow.find('[name="link"]').val(link);

			editWindow.find('[name="id_item_parent"] [value="'+item.id_item_parent+'"]').attr('selected', 'selected');

			editWindow.find('[name="reference_type"][value="'+item.references.type+'"]').attr('checked', 'checked');

			editWindow.find('[name="reference_url"]').val(item.references.url.uri);
			editWindow.find('[name="id_slot_reference"]').val(item.references.slot.id_slot);
			editWindow.find('[name="id_cell_reference"]').val(item.references.slot.id_cell);
			editWindow.find('[name="id_page_reference"]').val(item.references.page.id_page);

			editWindow.find('[name="submenu_type"] [value="'+item.subMenuType+'"]').attr('selected', 'selected');

			editWindow.find('[name="active"]').attr('checked', item.active ?'checked' :false);
			editWindow.find('[name="visible"]').attr('checked', item.visible ?'checked' :false);

			editWindow.find('[name="width"]').val(item.geometry.width);
			editWindow.find('[name="height"]').val(item.geometry.height);


			editWindow.find('.Menu-edit-item-form').bind('submit', function (ev) {
				ev.preventDefault();
				menu.saveMenuItem(id_item, function (resp) {
					/*item.text = editWindow.find('[name="name"]').val();
					item.link = editWindow.find('[name="link"]').val();
					item.id_item_parent = editWindow.find('[name="id_item_parent"] option:selected').val();
					item.references.type = editWindow.find('[name="reference_type"]:checked').val();
					item.references.url.uri = editWindow.find('[name="reference_url"]').val();
					item.references.slot.id_slot = editWindow.find('[name="id_slot_reference"]').val();
					item.references.slot.id_cell = editWindow.find('[name="id_cell_reference"]').val();
					item.references.page.id_page = editWindow.find('[name="id_page_reference"]').val();
					item.subMenuType = editWindow.find('[name="submenu_type"] option:selected').val();
					item.active = editWindow.find('[name="active"]:checked').length > 0?1:0;
					item.visible = editWindow.find('[name="visible"]:checked').length > 0?1:0;
					item.geometry.width = editWindow.find('[name="width"]').val();
					item.geometry.height = editWindow.find('[name="height"]').val();
					menuItem.attr('data-myshark-params', $.toJSON(item));*/
					myshark.loader.refresh();
					myshark.windows.close(editWindow);
				});
			});

			myshark.windows.window(editWindow);
	    
		}

		this.saveMenuItem = function (id_item, cb) {
			var el = $('#Menu-window-edit-'+id_item);
			var name = el.find('[name="name"]').val();
			var link = el.find('[name="link"]').val();
			var refType = el.find('[name="reference_type"]').val();
			var refUrl = el.find('[name="reference_url"]').val();
			var id_item_parent = el.find('[name="id_item_parent"]').val();
			var id_slot = el.find('[name="id_slot_reference"]').val();
			var id_cell = el.find('[name="id_cell_reference"]').val();
			var id_page = el.find('[name="id_page_reference"]').val();
			var submenuType = el.find('[name="submenu_type"]').val();
			var active = el.find('[name="active"]').attr('checked') == 'checked' ?1 :0;
			var visible = el.find('[name="visible"]').attr('checked') == 'checked' ?1 :0;
			var width = el.find('[name="width"]').val();
			var height = el.find('[name="height"]').val();
			myshark.loader.post({
				module: 'Menu',
				method: 'saveItem',
				id_item: id_item,
				name: name,
				link: link,
				reference_type: refType,
				reference_url: refUrl,
				id_item_parent: id_item_parent,
				id_slot_reference: id_slot,
				id_cell_reference: id_cell,
				id_page_reference: id_page,
				submenu_type: submenuType,
				active: active,
				visible: visible,
				width: width,
				height: height
			}, function (resp) {
				// Povedlo se
				myshark.windows.infoFlash(_t('Položka menu byla úspěšně uložena'));
				cb(resp);
			}, function (resp) {
				// nastala chyba
				myshark.windows.errorFlash(_t('Při ukládání položky menu vznikla chyba'));
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
