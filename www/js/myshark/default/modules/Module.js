/**
 * Obohatí o menu module
 * 
 */

(function ($) {
	var myshark = $.myshark;
	
	var module = new function () {
		var module = this;
		
		this.restart = function () {
			bindMenuIcons();
		}
		
		this.start = function () {
			bindMenuIcons();
		}
		
		
		var bindMenuIcons = function () {
			$('.edit_content_text').unbind('click').click(showEditContentTextWindow);
		}
		
		var showEditContentTextWindow = function (ev) {
			var contentEl = $(this).parent('.content');
			var content = $.parseJSON(contentEl.attr('data-myshark-params'));
			var id_content = content.id_content;
			var editWindow = $('#Content-window-edit').clone(true);
			editWindow.attr('id', 'Content-window-edit-'+id_content);
			
			myshark.windows.window(editWindow);
		}
		
	}
	
	// Před "načtením" stránky
	myshark.modules.module = module;
	
	// Po "načtením" stránky
	$(document).ready(function () {
		module.start();
	});
	
})(jQuery);