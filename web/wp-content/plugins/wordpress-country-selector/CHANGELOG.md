# Changelog
======
1.6.3
======
- NEW:	Save IP service lookups by saving data in cookie
- NEW:	You can now set an API key for service (when requets exceed 10.000 per Month)
		https://imgur.com/a/oey554v

======
1.6.2
======
- NEW:	Dropped Redux Framework support and added our own framework 
		Read more here: https://www.welaunch.io/en/2021/01/switching-from-redux-to-our-own-framework
		This ensure auto updates & removes all gutenberg stuff
		You can delete Redux (if not used somewhere else) afterwards
		https://www.welaunch.io/updates/welaunch-framework.zip
		https://imgur.com/a/BIBz6kz

======
1.6.1
======
- NEW:	Added MENA Region 
- NEW:	Removed the country <> continent subsection in plugin settings,
		because mena and africe can have the same countries
- FIX:	Updated Bot detection library (front and backend)

======
1.6.0
======
- NEW:	Added 5 new text field to plugin settings directly
		See: https://imgur.com/a/kbGwojQ

======
1.5.8
======
- FIX:	Removed cyprus from Asia continent

======
1.5.7
======
- FIX:	Force redirect and 0 seconds issue

======
1.5.6
======
- FIX:	PHP notices

======
1.5.5
======
- NEW:	Added a checkbox to general settings to deactivate getting sites 
		country by wp-admin users language
		General > Get current Sites Country by Language

======
1.5.4
======
- FIX:	Important update to the location API 

======
1.5.3
======
- FIX:	Continue popup style shows try correct page URLs
- FIX:	Added an ltrim slashes to dropdown widget

======
1.5.2
======
- FIX:	Performance optimizations for Redux when not admin
- FIX:	Added an ltrim slashes to try to correct page URL

======
1.5.1
======
- FIX:	Redirect issue

======
1.5.0
======
- NEW:	Redirect Default URL by Cookie
		If a user has choosen a country before and visits 
		the default site, he will be redirected. 
		Users still can access country URLs (other than the force redirect method).

======
1.4.4
======
- FIX:	Moved Turkey to EU
- FIX:	Always show popup caused infinite redirection when force redirection enabled

======
1.4.3
======
- FIX:	!IMPORTANT! Replaced Geolocation service -> Required update

======
1.4.2
======
- NEW:	Added prefilled translation files for DE, NL, FR, IT & ES
- FIX:	PHP Notices
- FIX:	Added translation support for modal strings

======
1.4.1
======
- FIX:	Added support for arabic / non-utf8 urls

======
1.4.0
======
- NEW:	Link to existing pages on other country site
		e.g. domain.com/test/ => domain.de/test/
		this requires urls to be equal in source and destination site
- NEW:	You can now use %s in widget or shortcode text
		%s will be replaced with the current country Name
- FIX:	Better current flag display for widget & shortcode

======
1.3.2
======
- FIX:	Issue with "stay at international"

======
1.3.1
======
- FIX:	Flags & Continents missing
- FIX:	Removed TGMPA plugin
- FIX:	Updated Translation FIles
- FIX:	Backend API Service changed also

======
1.3.0
======
- FIX:	PHP Notice issue

======
1.2.9
======
- NEW:	! Important Update !
		As of 1st of July the old geoip provider we used "https://freegeoip.net/json/" 
		is no longer available, we switched to a new one: "https://geoip.nekudo.com/"
		You need to update our plugin otherwise it won't work anymore after 1st of July

======
1.2.8
======
- FIX:  [ wordpress_country_selector ] shortcode now with ob buffering
- FIX:  Added Internation also to the country selector modal 
- FIX:  International Flag will be shown in widget / shortcode selector

======
1.2.7
======
- NEW:  Added international to country selector dropdown
- NEW:  Added international to country selector page
- FIX:  Removed East Germany

======
1.2.6
======
- FIX:  Removed option to always remove path and made it standard
- FIX:  Important Update for users_URL fix!
- FIX:  Stay at international page instead of United States

======
1.2.5
======
- NEW:  Remove path from Users URL setting
		See Advanced Settings
		This makes sure, that an end URL 
		path always gets stripped out.

======
1.2.4
======
- FIX: Next countries fix

======
1.2.3
======
- FIX: Small CSS bugs

======
1.2.2
======
- NEW:  Added prefix .wordpress-country-selector to all bootstrap classes
		If you have custom CSS, make sure you change e.g. ".modal" to 
		".wordpress-country-selector-modal"
- NEW:  Option to disalbe Bootstrap JS & CSS indepdent

======
1.2.1
======
- NEW:  Filter for available countries wordpress_country_selector_available_countries_filter
		allows you to sort flags for "continue" style 

======
1.2.0
======
- NEW:  Popup Style Continue & All Flags
		Shows a continue button to stay on current page 
		and below that all other countries as flags
- NEW:  Show popup always option
		Regardless of users country and sites country the popup will show
		This does not affect the cookie
- NEW:  Cookie will not also be set when a link on modal is clicked
- NEW:  Set text for the popup inside the settings panel
- NEW:  WPML Support
- FIX:  Small tweaks & performance issues

======
1.12.9
======
- FIX: undefined variables notice

======
1.12.8
======
- FIX: Country Selector page when translations were done

======
1.12.7
======
- FIX: Switched from .getJSON to .ajax in public JS file

======
1.12.6
======
- FIX: PHP 5.4 Support

======
1.12.5
======
- FIX: WPML support for AJAX check when no refferer is set

======
1.12.4
======
- NEW: WPML support for AJAX check
- NEW: Style option for country selector widget:
		Link: Simple link with current Flag to the country selector page
		Dropdown: List Dropdown with all created countries, link and their flag
- NEW: Shortcode to display the country selector widget
       How to use: [country_selector text="Select a country" style="link OR dropdown"]
- FIX: Map glyph for GB (United Kingdom)

======
1.12.3
======
- NEW: Page style dropdown
- FIX: Switched editor fields to raw text fields, because " (e.g. in a-Tags break the settings panel)
	   If you really want to have a link in the text fields use ' for the href attribute!
- FIX: Direct redirect when forced redirect & seconds = 0 now works again without delay 

======
1.12.2
======
- FIX: user logged in exclusion check for force redirect
- FIX: removed some console logs

======
1.12.1
======
- FIX: get_bloginfo('url') -> get_site_url()

=====
1.12.0
======
- NEW: Moved from Server Side to Frontend Rendering
	   Everything will be fetched via Javascript / AJAX 
	   So now it works with Caching plugins. 

=====
1.11.2
======
- FIX: Class WC_Geolocation already declared

======
1.11.1
======
- NEW: Set a cookie expiration in days

======
1.11.0
======
- NEW: Load your countries in a modal when somebody clicks on the widget link
		Example: See bottom right of Demo page and click the link

======
1.10.6
======
- FIX: Bootstrap Transitions function not loaded

======
1.10.5
======
- FIX: Class WC_GeoLocation error with newest version of WooCommerce plugin

======
1.10.4
======
- NEW: When Default URL shows up a new text appears with "Visit international Version"
- FIX: Removed the users Country <-> site's country check 
- FIX: Close button round 

======
1.10.3
======
- FIX: GEO ip class not found

======
1.10.2
======
- NEW: WPML support (changed get_site_url(); -> get_bloginfo( 'url');)

======
1.10.1
======
- FIX: filter not working when Modal disabled

======
1.10.0
======
- NEW: When you enter 0 for redirect seconds the user will be redirected directly without seeing a popup via WP_REDIRECT()
- FIX: Fix when Wordpress General -> Language was not set "correctly"

======
1.0.9
======
- FIX: Enable force redirect without popup

======
1.0.8
======
- NEW: Better plugin activation
- FIX: Better advanced settings page (ACE Editor for CSS and JS )
- FIX: array key exists

======
1.0.7
======
- FIX: Error when activating in Network

======
1.0.6
======
- FIX: Redux Error

======
1.0.5
======
- NEW: Removed the embedded Redux Framework for update consistency
//* PLEASE MAKE SURE YOU INSTALL THE REDUX FRAMEWORK PLUGIN *//

======
1.0.4
======
- FIX: WC Geolocation class error
- FIX: wc_clean error

======
1.0.3
======
- NEW: Use a better way to detect users country 

======
1.0.2
======
- FIX: Error when wordpress is setted up as a multisite

======
1.0.1
======
- NEW: debug information
- FIX: wrong echo of users country

======
1.0.0
======
- Inital release