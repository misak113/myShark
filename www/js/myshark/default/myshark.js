
/**
 * MyShark framework for myShark system
 * @autor Michael Žabka
 * @created 14. 12. 2011
 */


(function ($) {
    
    
	function MyShark () {
		var myshark = this;
	
		this.redirectHashmark = true;
		this.baseUrl = null;
		this.animate = null;
		this.debugMode = false;
	
	
	
		this.modules = new function () {
			var modules = this;
	    
			this.restart = function () {
				for (var i in modules) {
					try {
						var type = typeof modules[i];
						if (type != 'object') continue;
						modules[i].restart();
					} catch (e) {
						_d('Chyba při restartování modulu', e);
					}
				}
			}
		};
	
		this.windows = new function () {
			var win = this;
		
			this.ERROR = 'error';
			this.INFO = 'info';
			this.flashQueue = [];
			this.flashNowShowed = false;
	
			this.defaultDialogOptions = {
				width: 640,
				height: 480,
				show: 'highlight',
				hide: 'highlight',
				resizable: false
			};
		
			this.hideWindows = function () {
				$('#windows .window').hide();
			}
		
			this.showWindows = function () {
				win.dialog($('#windows .window'));
			}
	    
			this.errorFlash = function (text) {
				win.flash(text, win.ERROR);
			}
	    
			this.infoFlash = function (text) {
				win.flash(text, win.INFO);
			}
	    
			this.flash = function (text, type) {
				var window = $('<div/>').addClass('flash').addClass(type).html(text);
				$('#flashes').append(window);
				win.showFlashes();
			}

			this.window = function (el, addOptions) {
				if (typeof addOptions == 'undefined') addOptions = {};
				win.dialog(el, addOptions);
			}

			this.close = function (el) {
				el.dialog('close');
			}

			this.dialog = function (el, addOptions) {
				var options = el.attr('data-myshark-window-options') ?$.parseJSON(el.attr('data-myshark-window-options')) :{};
				options = $.extend(win.defaultDialogOptions, options, addOptions)
				el.dialog(options).bind('dialogclose', function (ev, ui) {
					el.dialog('destroy').remove();
				});
			}
	    
			this.showFlashes = function () {
				$('#flashes .flash').each(function () {
					var flash = $(this);
					win.flashQueue.push(flash);
				});
				win.showNextFlash();
			}
	    
			this.showNextFlash = function () {
				var flash = win.flashQueue.pop();
				if (flash && !win.flashNowShowed) {
					var showTime = 80*flash.html().length;
					showTime = showTime < 1500 ?1500: showTime;
					flash.fadeIn('fast').delay(showTime).fadeOut('fast', function () {
						flash.remove();
						win.flashNowShowed = false;
						win.showNextFlash();
					});
					win.flashNowShowed = true;
				}
			}
		
		}
	
		this.url = new function () {
			var url = this;
		
			this.getActualPath = function () {
				var href = $(location).attr('href').split('#');
				href = href[0];
				return url.getPath(href);
			}
	    
			this.getActualHashPath = function () {
				var hash = $(location).attr('hash');
				hash = hash.replace('#', '');
				return hash;
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
			var loader = this;
			this.PROXY_CACHE_PAGES = 'proxy_cache_pages';
		
			this.load = function (url, cb, forceLoad) {
				loader.showLoading();
				if (!cb) {
					cb = function () {};
				}
				var key = url;
				var pages = myshark.sessionStorage.getItem(loader.PROXY_CACHE_PAGES);
				pages = pages == null ?{} :pages;
				if (typeof pages[key] != 'undefined' && forceLoad != true && myshark.debugMode == false) {
					loader.hideLoading();
					cb(pages[key]);
					return;
				}
				$.ajax({
					url: myshark.baseUrl+'/'+url,
					dataType: 'html',
					type: 'GET',
					success: function (resp) {
						pages[key] = resp;
						myshark.sessionStorage.setItem(loader.PROXY_CACHE_PAGES, pages);
						loader.hideLoading();
						cb(resp);
					},
					error: function (resp) {
						loader.hideLoading();
			
						if (resp.status == 401) {
							myshark.redirectHashmark = true;
							myshark.url.redirectByHash();
						}
						myshark.windows.errorFlash(_t('Při načítání stránky došlo k chybě. (%s - %s)', [
							typeof resp.status != 'undefined' ?resp.status :'0',
							typeof resp.statusText != 'undefined' ?resp.statusText :_t('Neznámá chyba')
							]));
						_d('Chyba při načítání stránky', resp);
					}
				});
			}
	    
			this.post = function (params, cb, errorCb) {
				loader.showLoading();
				$.ajax({
					url: myshark.baseUrl+'/'+myshark.url.getActualHashPath(),
					dataType: 'html',
					type: 'POST',
					data: params,
					success: function (resp) {
						myshark.sessionStorage.removeItem(loader.PROXY_CACHE_PAGES);
						loader.hideLoading();
						cb(resp);
					},
					error: function (resp) {
						loader.hideLoading();
			
						if (resp.status == 401) {
							myshark.redirectHashmark = true;
							myshark.url.redirectByHash();
						}
						myshark.windows.errorFlash(_t('Při načítání stránky došlo k chybě. (%s - %s)', [
							typeof resp.status != 'undefined' ?resp.status :'0',
							typeof resp.statusText != 'undefined' ?resp.statusText :_t('Neznámá chyba')
							]));
						errorCb(resp);
					}
				});
			}

			this.refresh = function (cb) {
				loader.load(myshark.url.getActualHashPath(), cb, true);
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

			this.Storage = function (storage) {
				var real = true;
				if (typeof storage == 'undefined') {
					storage = myshark.util.tempStorage;
					real = false;
				}
				this.getItem = function (itemName) {
					var itemValue = storage.getItem(itemName);
					try {
						itemValue = $.parseJSON(itemValue);
					} catch (e) {}
					return itemValue;
				}
				this.setItem = function (itemName, itemValue) {
					try {
						itemValue = $.toJSON(itemValue);
					} catch (e) {}
					storage.setItem(itemName, itemValue);
				}
				this.removeItem = function (itemName) {
					return storage.removeItem(itemName);
				}
				this.clear = function () {
					storage.clear();
				}
				this.isRealStorage = function () {
					return real;
				}
			}

			this.tempStorage = new function () {
				this.storage = {};

				this.setItem = function (name, value) {
					this.storage[name] = value;
				}
				this.getItem = function (name) {
					return typeof this.storage[name] != undefined ?this.storage[name] :null;
				}
				this.removeItem = function (name) {
					this.storage[name] = null;
				}
				this.clear = function () {
					this.storage = {};
				}
			}

		}

		this.localStorage = new myshark.util.Storage(localStorage);
		this.sessionStorage = new myshark.util.Storage(sessionStorage);
		this.tempStorage = new myshark.util.Storage(myshark.util.tempStorage);

		this.debug = new function () {

			this.detect = function () {
				if (myshark.baseUrl.indexOf("localhost") > -1) {
					myshark.debugMode = true;
				}
			}
		}
	
	}

	
	var myshark = new MyShark($);
	$.myshark = myshark;

    
	
	// Před "načtením" stránky
	myshark.windows.hideWindows();
	
	// Po "načtení" stránky
	$(document).ready(function () {
		myshark.debug.detect();
		myshark.url.redirectByHash();
    
		// Po "načtení" celého okna
		$(window).bind('load', function () {
			// windows
			myshark.windows.showWindows();
			myshark.windows.showFlashes();
		//myshark.loader.hideLoading();
		});
		
	});
	
})(jQuery);

