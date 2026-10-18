var e107 = e107 || {'settings': {}, 'behaviors': {}};

(function ($)
{
	'use strict';


	/**
	 * Lightbox: the picture opens centred on the page, with arrows, keyboard and swipe.
	 */
	var vstoreLightbox = (function ()
	{
		var $box = null;
		var $img = null;
		var $caption = null;
		var items = [];
		var index = 0;
		var opener = null;
		var onChange = null;
		var touchX = null;

		function text(key)
		{
			return (e107.settings.vstore.gallery || {})[key] || '';
		}

		function button(cls, label, icon)
		{
			return $('<button type="button"></button>')
				.addClass(cls)
				.attr('aria-label', label)
				.html('<i class="fa ' + icon + '" aria-hidden="true"></i>');
		}

		function go(i)
		{
			index = (i + items.length) % items.length;

			var item = items[index];

			$box.addClass('is-loading');
			$img.attr('src', item.large).attr('alt', item.alt);
			$caption.text(item.alt ? item.alt + ' (' + (index + 1) + '/' + items.length + ')' : (index + 1) + ' / ' + items.length);

			// the next and the previous picture load in the background
			[index + 1, index - 1].forEach(function (n)
			{
				var near = items[(n + items.length) % items.length];
				if (near && near.large !== item.large)
				{
					new Image().src = near.large;
				}
			});

			if (onChange)
			{
				onChange(index);
			}
		}

		function close()
		{
			if (!$box)
			{
				return;
			}

			$(document).off('keydown.vstoreLightbox');
			$('html').removeClass('vstore-lb-open');
			$box.removeClass('is-open');

			var $closing = $box;
			setTimeout(function () { $closing.remove(); }, 250);
			$box = null;

			if (opener)
			{
				opener.focus();
			}
		}

		function keys(e)
		{
			if (e.key === 'Escape')
			{
				close();
			}
			else if (e.key === 'ArrowRight' && items.length > 1)
			{
				go(index + 1);
			}
			else if (e.key === 'ArrowLeft' && items.length > 1)
			{
				go(index - 1);
			}
			else if (e.key === 'Tab')
			{
				// the focus stays inside the dialog
				var $focusable = $box.find('button:visible');
				var pos = $focusable.index(document.activeElement);

				if (e.shiftKey && pos <= 0)
				{
					$focusable.last().focus();
					e.preventDefault();
				}
				else if (!e.shiftKey && pos === $focusable.length - 1)
				{
					$focusable.first().focus();
					e.preventDefault();
				}
			}
		}

		function build()
		{
			$img = $('<img alt="">').on('load error', function () { $box.removeClass('is-loading'); });
			$caption = $('<figcaption></figcaption>');

			$box = $('<div class="vstore vstore-lb" role="dialog" aria-modal="true"></div>')
				.attr('aria-label', text('enlarge'))
				.append(button('vstore-lb-close', text('close'), 'fa-times').on('click', close))
				.append(button('vstore-lb-prev', text('prev'), 'fa-chevron-left').on('click', function () { go(index - 1); }))
				.append($('<figure class="vstore-lb-stage"></figure>').append($img, $caption))
				.append(button('vstore-lb-next', text('next'), 'fa-chevron-right').on('click', function () { go(index + 1); }))
				.on('click', function (e)
				{
					// a click on the dark area closes it
					if ($(e.target).is('.vstore-lb, .vstore-lb-stage'))
					{
						close();
					}
				})
				.on('touchstart', function (e) { touchX = e.originalEvent.touches[0].clientX; })
				.on('touchend', function (e)
				{
					var dx = e.originalEvent.changedTouches[0].clientX - touchX;

					if (touchX !== null && Math.abs(dx) > 50 && items.length > 1)
					{
						go(index + (dx < 0 ? 1 : -1));
					}
					touchX = null;
				});
		}

		return {
			/**
			 * @param {Array} list items with large (url) and alt
			 * @param {number} start index of the first picture
			 * @param {Function} changed called with the index when the picture changes
			 * @param {Element} from element that gets the focus back on close
			 */
			open: function (list, start, changed, from)
			{
				items = list;
				onChange = changed;
				opener = from;

				build();
				$box.toggleClass('is-single', items.length < 2);
				$('body').append($box);
				$('html').addClass('vstore-lb-open');
				go(start);

				$(document).on('keydown.vstoreLightbox', keys);
				setTimeout(function () { $box.addClass('is-open'); $box.find('.vstore-lb-close').focus(); }, 20);
			}
		};
	}());

	/**
	 * Namespace for vstore related settings.
	 */
	e107.settings.vstore = e107.settings.vstore || {};

	/**
	 * Product gallery: the thumbnails change the main picture and a click on it opens the lightbox.
	 * @type {{attach: e107.behaviors.vstoreGallery.attach}}
	 */
	e107.behaviors.vstoreGallery = {
		attach: function (context, settings)
		{
			$(context).find('.vstore-pv-gallery').one('vstore-gallery').each(function ()
			{
				var $main = $(this).find('.vstore-pv-main');
				var $mainLink = $main.find('a').first();
				var $thumbs = $(this).find('.vstore-pv-thumb a');

				if (!$mainLink.length)
				{
					return;
				}

				var items = [];
				var $sources = $thumbs.length ? $thumbs : $mainLink;

				$sources.each(function ()
				{
					var $a = $(this);

					items.push({
						large: $a.attr('href'),
						main: $a.data('standard'),
						alt: $a.find('img').attr('alt') || ''
					});
				});

				var current = 0;

				function show(i)
				{
					current = i;

					// the picture shown on the page is the medium one, never the small thumbnail
					$main.find('img').first().attr('src', items[i].main).removeAttr('srcset');
					$mainLink.attr('href', items[i].large);
					$thumbs.parent().removeClass('is-active').eq(i).addClass('is-active');
				}

				$thumbs.each(function (i)
				{
					$(this).on('click', function (e)
					{
						e.preventDefault();
						show(i);
					});
				});

				$mainLink.on('click', function (e)
				{
					e.preventDefault();
					vstoreLightbox.open(items, current, show, $mainLink[0]);
				});

				$thumbs.parent().first().addClass('is-active');
				$main.append('<span class="vstore-pv-expand" aria-hidden="true"><i class="fa fa-expand"></i></span>');
			});
		}
	};

	/**
	 * Product showcase menu: the category pills show the matching panel instead of leaving the page.
	 * Without JavaScript the pills are plain links to the category pages.
	 */
	e107.behaviors.vstoreShowcase = {
		attach: function (context, settings)
		{
			$(context).find('.vstore-showcase').one('vstore-showcase').each(function ()
			{
				var $box = $(this);
				var $pills = $box.find('.vstore-pill[data-vs-cat]');

				if (!$pills.length)
				{
					return;
				}

				$pills.on('click', function (e)
				{
					e.preventDefault();

					var cat = String($(this).data('vs-cat'));

					$pills.removeClass('active').removeAttr('aria-current');
					$(this).addClass('active').attr('aria-current', 'true');

					$box.find('.vstore-showcase-panel').each(function ()
					{
						$(this).prop('hidden', String($(this).data('vs-panel')) !== cat);
					});
				});
			});
		}
	};

	/**
	 * @type {{attach: e107.behaviors.vstoreCartQty.attach}}
	 */
	e107.behaviors.vstoreCartQty = {
		attach: function (context, settings)
		{
			$(context).find('.cart-qty, #cart-coupon-code').one('vstore-cart-qty').each(function ()
			{
				var $this = $(this);

				$this.keyup(function ()
				{
					$(".cart-qty-submit").show(0);
					$(".cart-checkout").hide(0);
				});
			});
		}
	};

	/**
	 * add item to cart via ajax
	 * @type {{attach: e107.behaviors.vstoreCartAdd.attach}}
	 */
	var vstoreReduceMotion = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

	/**
	 * A copy of the product picture flies to the cart icon; done() runs when it lands.
	 */
	function vstoreFlyToCart($btn, done)
	{
		var $target = $('#vstore-cart-icon:visible').first();
		if (!$target.length)
		{
			$target = $('.navbar-toggler:visible').first();
		}

		var $src = $btn.closest('article, .vstore-pv').find('img:visible').first();
		if (!$src.length)
		{
			$src = $btn;
		}

		if (vstoreReduceMotion || !$target.length || !$src[0].animate)
		{
			done();
			return;
		}

		var from = $src[0].getBoundingClientRect();
		var to = $target[0].getBoundingClientRect();
		var size = Math.max(40, Math.min(72, from.width / 2));
		var left = from.left + from.width / 2 - size / 2;
		var top = from.top + from.height / 2 - size / 2;
		var dx = to.left + to.width / 2 - size / 2 - left;
		var dy = to.top + to.height / 2 - size / 2 - top;

		var $fly = ($src.is('img') ? $('<img alt="">').attr('src', $src.attr('src')) : $('<span></span>'))
			.addClass('vstore-fly')
			.css({left: left, top: top, width: size, height: size});

		$('body').append($fly);

		var finished = false;
		var finish = function ()
		{
			if (finished)
			{
				return;
			}

			finished = true;
			$fly.remove();
			done();
		};

		var flight = $fly[0].animate([
			{transform: 'translate(0, 0) scale(1)', opacity: 1},
			{transform: 'translate(' + (dx * 0.55) + 'px, ' + (dy * 0.55 - 60) + 'px) scale(.85)', opacity: 1, offset: 0.55},
			{transform: 'translate(' + dx + 'px, ' + dy + 'px) scale(.25)', opacity: 0.25}
		], {duration: 750, easing: 'cubic-bezier(.45, 0, .55, 1)'});

		flight.onfinish = finish;

		// a hidden tab pauses animations: the counter must still change
		setTimeout(finish, 1100);
	}

	/**
	 * The cart icon and its counter pulse once.
	 */
	function vstoreBumpCart()
	{
		if (vstoreReduceMotion)
		{
			return;
		}

		var $icon = $('#vstore-cart-icon');
		$icon.removeClass('vstore-cart-bump');
		void $icon[0].offsetWidth; // restart the animation
		$icon.addClass('vstore-cart-bump');
		setTimeout(function () { $icon.removeClass('vstore-cart-bump'); }, 900);
	}

	/**
	 * The button shows a check for a moment.
	 */
	function vstoreButtonAdded($btn, label)
	{
		if ($btn.data('vstoreHtml') === undefined)
		{
			$btn.data('vstoreHtml', $btn.html());
		}

		$btn.addClass('is-added').html('<i class="fa fa-check" aria-hidden="true"></i> ').append($('<span></span>').text(label));

		clearTimeout($btn.data('vstoreTimer'));
		$btn.data('vstoreTimer', setTimeout(function ()
		{
			$btn.removeClass('is-added').html($btn.data('vstoreHtml'));
			$btn.removeData('vstoreHtml');
		}, 1800));
	}

	/**
	 * Small message with a link to the cart, announced to screen readers.
	 */
	function vstoreToast(text, linkText, url)
	{
		$('.vstore-toast').remove();

		var $toast = $('<div class="vstore vstore-toast" role="status" aria-live="polite"></div>')
			.append('<i class="fa fa-check-circle" aria-hidden="true"></i> ')
			.append($('<span></span>').text(text))
			.append($('<a></a>').attr('href', url).text(linkText));

		$('body').append($toast);
		setTimeout(function () { $toast.addClass('is-visible'); }, 20);
		setTimeout(function ()
		{
			$toast.removeClass('is-visible');
			setTimeout(function () { $toast.remove(); }, 400);
		}, 3800);
	}

	e107.behaviors.vstoreCartAdd = {
		attach: function (context, settings)
		{
			$(context).find('.vstore-add').one('vstore-cart-add').each(function (e)
			{
				$(this).click(function(e){
					e.preventDefault();

					var $btn = $(this);
					if ($btn.hasClass('disabled'))
					{
						return;
					}

					var url = settings.vstore.cart.url;
					var itemid = $(this).data('vstore-item');

					url += (url.indexOf('?')>=0 ? '&' : '?') + 'mode=cart&add=' + itemid;

					var qty = parseInt($('#vstore-item-qty-' + itemid).val(), 10);
					if (qty > 1)
					{
						url += '&qty=' + qty;
					}


					if ($('#vstore-item-vars-' + itemid).length>0)
					{
						$('select.vstore-item-var').each(function(i, v){

							if ($(v).data('id') == itemid)
							{
								var $option = $('#' + $(v).attr('id') + ' option:selected');
								if ($option.data('type')!= '' && $option.val() != '')
								{
									url += '&itemvar[]=' + encodeURIComponent($option.data('id') + '-' + $option.val());
								}
							}

						});
					}


					$.get(url, function(resp){
						var msg = (typeof resp != 'undefined') ? resp : '';
						if (msg.substr(0, 2) == 'ok')
						{
							// if ok, update cart menu with new content
							msg = $.trim(msg.substr(2));
							$('#vstore-cart-dropdown').html(msg);
							var itemcount = $('#vstore-item-count').val();
							if (itemcount <= 0) itemcount = 0;

							var cartText = e107.settings.vstore.cart;
							vstoreButtonAdded($btn, cartText.added);
							vstoreToast(cartText.addedtext, cartText.viewcart, cartText.url);

							// the counter changes when the picture lands on the cart icon
							vstoreFlyToCart($btn, function ()
							{
								if ($('#vstore-cart-icon .badge').length>0)
								{
									$('#vstore-cart-icon .badge').html(itemcount);
								}
								else if ($('#vstore-cart-icon .badge-pill').length>0)
								{
									$('#vstore-cart-icon .badge-pill').html(itemcount);
								}
								$('#vstore-cart-icon').addClass('active');
								vstoreBumpCart();
							});

							$('li.dropdown.vstore-storecart').addClass('open');
							return;
						}
                                                else {
							$btn.removeClass("btn-success btn-primary").addClass("disabled btn-default btn-secondary").html(e107.settings.vstore.cart.outofstock);
						}
						// Print our any (error) message
						// $('#uiAlert').html(msg);
						vstorePrintMessage(msg);

					});
				});
			});
		}
	};


	/**
	 * Update price on variation change
	 */
	e107.behaviors.vstorePriceUpdate = {
		attach: function (context, settings)
		{
			$(context).find('select.vstore-item-var').one('vstore-price-update').each(function (e)
			{
				$(this).change(function(e){
					var itemid = $(this).data('id');
					var varid = $(this).data('item');
					var baseprice = parseFloat($('.vstore-item-baseprice-'+itemid).val());
					var varprice = baseprice;
					var itemvars = [];

					$('select.vstore-item-var').each(function(i, v){
					var selected=true;

						if ($(v).data('id') == itemid)
						{
							var $option = $('#' + $(v).attr('id') + ' option:selected');
							if (selected) {
								itemvars.push({'id': $option.data('item'), 'item': $option.data('id'), 'val': $option.val()});
							}
							selected = false;
							var val = parseFloat($option.data('val'));
							if (val > 0.0)
							{
								switch($option.data('op'))
								{
									case '%':
										varprice += baseprice * (val / 100.0);
										break;
									case '+':
										varprice += val;
										break;
									case '-':
										varprice -= val;
										break;
								}
							}
						}

					});

					var inStock = vstoreCheckInventory(itemid, varid, itemvars);
					if (inStock)
					{
						$('.vstore-add-item-' + itemid).removeClass('btn-default disabled').each(function(){
							var $b = $(this), label = $b.data('vstore-label'); // quote buttons keep their caption
							$b.addClass(label ? 'btn-primary' : 'btn-success').html('<span class="glyphicon glyphicon-shopping-cart"></span> ' + (label ? label : settings.vstore.cart.addtocart));
						});
						$('.vstore-item-avail-' + itemid).removeClass('label-danger').addClass('label-success').html(settings.vstore.cart.available);
					}
					else
					{
						$('.vstore-add-item-' + itemid).removeClass('btn-success btn-primary').addClass('btn-default btn-secondary disabled').html(settings.vstore.cart.outofstock);
						$('.vstore-item-avail-' + itemid).removeClass('label-success').addClass('label-danger').html(settings.vstore.cart.outofstock);
					}

					// fixes #92: missing currency symbol after selecting a new variation
					var currency = $('#vstore-currency-symbol').text();
					if (currency.substr(0, 1) == 1) {
						$('.vstore-item-price-'+itemid).text(varprice.toFixed(2) + ' ' + currency.substr(1));
					}else{
						$('.vstore-item-price-' + itemid).text(currency.substr(1) + ' ' + varprice.toFixed(2));
					}

				});
			});
		}
	};



	/**
	 * add active class to selected payment type
	 */
	e107.behaviors.vstoreSetGateway = {
		attach: function (context, settings)
		{
			// MB WAY sends the payment request to the customer's mobile number: the phone becomes mandatory
			var togglePhoneRequired = function ()
			{
				var mbway = $('.vstore-gateway-radio[value="mollie_mbway"]').is(':checked');
				$('input[name="cust[phone]"]').prop('required', mbway);
			};

			$(context).find('.vstore-gateway-radio').one('vstore-set-gateway').each(function (e)
			{

				$(this).change(function(){
					$(".vstore-gateway").removeClass("active");
					$(this).parent().addClass("active");
					togglePhoneRequired();
				});
			});

			togglePhoneRequired();
		}
	}

})(jQuery);

/** 
 * Reset/empty cart and update menu
 */
function vstoreCartReset()
{
	var url = e107.settings.vstore.cart.url;
	var resetUrl = url + (url.indexOf('?')>=0 ? '&' : '?') + 'reset=1';
	var redirectUrl = '';
	if (location.href.indexOf(e107.settings.vstore.url) >= 0) {
		redirectUrl = e107.settings.vstore.url;
	}

	$.get(resetUrl, function(resp){
		var msg = (typeof resp != 'undefined') ? resp : '';
		if (msg.substr(0, 2) == 'ok') {
			if (redirectUrl.length > 0) {
				// Cart resetted => Redirect to shop start page
				location.href = redirectUrl;
			} else {
				// update cart menu with new content if not in the shop
				msg = msg.substr(2);
				$('#vstore-cart-dropdown').html(msg);
				if ($('#vstore-cart-icon .badge').length>0)
				{
					$('#vstore-cart-icon .badge').html(0);
				}
				else if ($('#vstore-cart-icon .badge-pill').length>0)
				{
					$('#vstore-cart-icon .badge-pill').html(0);
				}
			}
			return;
		}
	});

};

/**
 * Refresh cart menu item
 */
function vstoreCartRefresh()
{
	var url = e107.settings.vstore.cart.url;

	url += (url.indexOf('?')>=0 ? '&' : '?') + 'refresh=1';
	

	$.get(url, function(resp){
		var msg = (typeof resp != 'undefined') ? resp : '';
		if (msg.substr(0, 2) == 'ok')
		{
			// if ok, update cart menu with new content
			msg = msg.substr(2);
			$('#vstore-cart-dropdown').html(msg);
			var itemcount = $('#vstore-item-count').val();
			if (itemcount <= 0) itemcount = 0;
			if ($('#vstore-cart-icon .badge').length>0)
			{
				$('#vstore-cart-icon .badge').html(itemcount);
			}
			else if ($('#vstore-cart-icon .badge-pill').length>0)
			{
				$('#vstore-cart-icon .badge-pill').html(itemcount);
			}
			return;
		}
	});

};

/**
 * Check if the given iten or item/itemvar combination is in stock
 * @param {int} itemid 
 * @param {array} itemvars 
 */
function vstoreCheckInventory(itemid, varid, itemvars)
{
	if (typeof itemid == 'undefined' || parseInt(itemid, 10) <= 0)
	{
		return false;
	}
	var stock = e107.settings.vstore.stock['x'+itemid+'-'+varid];
	if (typeof stock == 'undefined')
	{
		return false;
	}

	var result = 0;
	if (typeof itemvars == 'undefined')
	{
		result = stock;
	}
	else if ($.isArray(itemvars))
	{
		for(var i=0; i<itemvars.length; i++)
		{
			if (itemvars[i].item == varid)
			{
				if (typeof stock == 'object'){
					result = stock[itemvars[i].val];
					if (typeof result == 'object'){
						result = result[itemvars[i+1].val];
					}
				}
				else
				{
					result = stock;
				}
				break;
			}
		}
	}


	if (typeof result == 'undefined')
	{
		return false;
	}


	if ($.isNumeric(result) && result != 0)
	{
		return true;
	}
	return false;

}

function vstorePrintMessage(msg)
{
	if ($('#uiAlert').length > 0)
	{	
		$('#uiAlert').html(msg);
	}
	else
	{
		$('#breadcrumb').after('<div id="uiAlert">' + msg + '</div>');	
	}
	$('.s-message.fade').removeClass('fade');
	$('#uiAlert.notifications').css({'width': 'auto', 'left': 'auto', 'margin': 'auto 10%' });
}