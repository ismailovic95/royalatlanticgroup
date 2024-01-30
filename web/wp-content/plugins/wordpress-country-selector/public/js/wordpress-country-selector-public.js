(function( $ ) {
	'use strict';

	// Create the defaults once
	var pluginName = "countrySelector",
		defaults = {
			country_selector_modal: "#country_selector_modal",
			country_selector_modal_close: ".country_selector_modal_close",
			modal_backdrop: ".modal-backdrop",
			country_selector_modal_stay: ".country_selector_modal_stay",
			
			country_selector_modal_page: "#country_selector_modal_page",
			country_selector_modal_page_show: "#country_selector_modal_page_show",
			country_selector_modal_page_close: "#country_selector_modal_page_close",
			country_selector_modal_goto: ".country_selector_modal_goto",
			country_selector_modal_flag: ".country_selector_modal_flag",
			cookie_lifetime: 60,
		};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element = element;
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;

		this._name = pluginName;
		this.init();
	}

	// Avoid Plugin.prototype conflicts
	$.extend( Plugin.prototype, {
		init: function() {
			var that = this;
			this.window = $(window);
			this.documentHeight = $( document ).height();
			this.windowHeight = this.window.height();

            var botPattern = "/bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex|crawl|spider/";
            var re = new RegExp(botPattern, 'i');
            if (re.test(navigator.userAgent)) {
                return false;
            }

			if(!this.isEmpty($(this.settings.country_selector_modal_page))){
				this.initPageModal();
			}
			this.initContinentSelector();
			this.getUsersCountry();
			this.getUsersLanguage();
			this.initDropdown();
			this.saveCountrySelection();
				
		},
		initModal: function(modal) {
			var that = this;
			var country_selector_modal = $(this.settings.country_selector_modal);
			var country_selector_modal_close = $(this.settings.country_selector_modal_close);
			var country_selector_modal_stay = this.settings.country_selector_modal_stay;
			var country_selector_modal_goto = this.settings.country_selector_modal_goto;
			var country_selector_modal_flag = this.settings.country_selector_modal_flag;

			var modal_title = country_selector_modal.find('.modal-title');
			var modal_text = country_selector_modal.find('.country_selector_modal_text');
			var modal_buttons = country_selector_modal.find('.country_selector_modal_buttons');

			modal_title.html(modal.modal_header);
			modal_text.html(modal.modal_text);
			modal_buttons.html(modal.modal_buttons);

			if(this.isEmpty(this.getCookie('country_selector_modal_cookie')) || this.getParameterByName('country') === "debug") {
		    	country_selector_modal.show();
			    country_selector_modal.modal('show');
			}

		    country_selector_modal_close.on('click', function(){
		    	country_selector_modal.hide();
		    	$('.modal-backdrop').remove();
			    country_selector_modal.modal('hide');
		    });

		    country_selector_modal.on('click', country_selector_modal_stay, function(e){
			    e.preventDefault();
			    var href = this.href;
		    	country_selector_modal.hide();
		    	$('.modal-backdrop').remove();
			    country_selector_modal.modal('hide');
		    });

			country_selector_modal.on('click', country_selector_modal_goto, function(e){
			    e.preventDefault();
			    var href = this.href;
				that.createCookie('country_selector_modal_cookie', 'false', that.settings.cookie_lifetime);		    
		    	window.location = href;
		    });

			country_selector_modal.on('click', country_selector_modal_flag, function(e){
			    e.preventDefault();
			    var href = this.href;
				that.createCookie('country_selector_modal_cookie', 'false', that.settings.cookie_lifetime);		    
		    	window.location = href;
		    });

			country_selector_modal.on('hide.bs.modal', function (e) {
				that.createCookie('country_selector_modal_cookie', 'false', that.settings.cookie_lifetime);
			});
		},
		initPageModal: function(callback) {
			var that = this;
			var country_selector_modal_page = $(this.settings.country_selector_modal_page);
			var country_selector_modal_page_show = $(this.settings.country_selector_modal_page_show);
			var country_selector_modal_page_close = $(this.settings.country_selector_modal_page_close);

			country_selector_modal_page_show.on('click', function(e){
				e.preventDefault();
		    	country_selector_modal_page.show();
			    country_selector_modal_page.modal('show');
		    });
			

		    country_selector_modal_page_close.on('click', function(){
		    	country_selector_modal_page.hide();
		    	$('.modal-backdrop').remove();
			    country_selector_modal_page.modal('hide');
		    });
		},
		initContinentSelector: function() {
			var country_selector_continent = $('.country_selector_continent');
			var continent_to_show;

			country_selector_continent.on('click', function(e) {
				e.preventDefault();
				continent_to_show = $(this).data('continent');

				$('.country_selector_countries_by_continent').fadeOut();
				$('.country_selector_countries_' + continent_to_show).fadeIn();
			});
		},
		checkCountrySelector: function(countryCode) {
			var that = this;

			var sites_locale = $('html').attr('lang');
			var url = window.location.href;
			var lang = that.getUsersLanguage();

			$.ajax({
				url: that.settings.ajax_url,
				type: 'post',
				dataType: 'JSON',
				data: {
					action: 'check_country_selector',
					country: countryCode,
					// url: url,
					lang: lang,
					sites_locale: sites_locale,
				},
				success : function( response ) {

					if(that.getParameterByName('country') === "debug") {
						console.log(response);
					}
					
					if( (response.show_popup === "1") || (that.getParameterByName('country') === "debug")) {

						if(response.force_redirect === "1") {

							if(response.force_redirect_exclude_logged_in === "1" && response.logged_in === "1") {

							} else {
								if(response.force_redirect_seconds === "0") {
									 window.location.replace(response.target_URL);
									 return;
								} else {
									var seconds = parseInt(response.force_redirect_seconds) * 1000;
									setTimeout(function() {
										window.location.replace(response.target_URL);
									}, seconds);
								}
							}
						}

						var URLchoosen = that.getCookie('country_selector_url');
						if(response.is_default && 
							URLchoosen && 
							that.settings.redirectOnCookie == "1" 
							&& that.getParameterByName('redirect') !== "false"
							&& URLchoosen !== response.users_url
						) {
							window.location.replace(URLchoosen);
						}

						that.initModal(response);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
				    console.log(jqXHR);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		},
		getUsersCountry : function() {
			var that = this;

			var url = "https://extreme-ip-lookup.com/json/";

			if(that.settings.apiKey != "") {
				url += '?key=' + that.settings.apiKey;
			}

			var countryCodeExists = that.getCookie('country_selector_country_code');
			if(countryCodeExists && that.getParameterByName('country') !== "debug") {
				that.checkCountrySelector(countryCodeExists);
				return;
			}

			$.ajax({
				// url: "https://ip.nf/me.json",
				url: url,
				type: 'get',
				dataType: 'json',
				success : function( response ) {
					if(that.getParameterByName('country') === "debug") {
						console.log(response);
					}

					that.createCookie('country_selector_country_code', response.countryCode, that.settings.cookie_lifetime);

					that.checkCountrySelector(response.countryCode);
				},
				error: function(jqXHR, textStatus, errorThrown) {
				    console.log(jqXHR);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
	        });
        },
        getUsersLanguage : function() {
        	return navigator.language || navigator.userLanguage; 
        },
        initDropdown : function() {

        	var continentSelect = $('.country_selector_continents_dropdown select');
        	var countriesSelect = $('.country_selector_countries_dropdown select');
        	var dropdownButton = $('#country_selector_dropdown_button');

        	continentSelect.on('change', function(e){
        		var continentSelected = $(this).val();
        		
        		$('.country_selector_countries_dropdown').fadeOut();
        			$('#country_selector_countries_dropdown_' + continentSelected).fadeIn();	
        	});
        	
        	countriesSelect.on('change', function(e){
        		var countrySelected = $(this).val();
        		
        		if(countrySelected !== "") {
        			dropdownButton.removeClass('disabled').attr('href', countrySelected);
        		} else {
        			dropdownButton.addClass('disabled').attr('href', '#');
        		}
        	});

        },
        saveCountrySelection : function() {

        	var that = this;

        	$(document).on('click', '.country_selector_modal_goto, .country_selector_modal_stay, .country_selector_country a, .country_selector_modal_flag', function(e) {
        		var $this = $(this);
        		var url = $this.attr('href');

        		if(url == "") {
        			return false;
        		}

        		that.createCookie('country_selector_url', url, that.settings.cookie_lifetime);		    
        	});

    	},
		//////////////////////
		///Helper Functions///
		//////////////////////
		isEmpty: function(obj) {

		    if (obj == null)		return true;
		    if (obj.length > 0)		return false;
		    if (obj.length === 0)	return true;

		    for (var key in obj) {
		        if (hasOwnProperty.call(obj, key)) return false;
		    }

		    return true;
		},
		sprintf: function parse(str) {
		    var args = [].slice.call(arguments, 1),
		        i = 0;

		    return str.replace(/%s/g, function() {
		        return args[i++];
		    });
		},
		getCookie: function(cname) {
		    var name = cname + "=";
		    var ca = document.cookie.split(';');
		    for(var i=0; i<ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0)==' ') c = c.substring(1);
		        if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
		    }
		    return "";
		},
		createCookie: function(name, value, days) {
			var expires = "";

		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime()+(days * 24 * 60 * 60 * 1000));
		        var expires = "; expires="+date.toGMTString();
		    }

		    document.cookie = name + "=" + value+expires + "; path=/";
		},
		getParameterByName: function(name, url) {
		    if (!url) url = window.location.href;
		    name = name.replace(/[\[\]]/g, "\\$&");
		    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
		        results = regex.exec(url);
		    if (!results) return null;
		    if (!results[2]) return '';
		    return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
	} );

	// Constructor wrapper
	$.fn[ pluginName ] = function( options ) {
		return this.each( function() {
			if ( !$.data( this, "plugin_" + pluginName ) ) {
				$.data( this, "plugin_" +
					pluginName, new Plugin( this, options ) );
			}
		} );
	};

	$(document).ready(function() {

		$('body').countrySelector(country_selector_options);

	} );

})( jQuery );