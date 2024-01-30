<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'weLaunch' ) && ! class_exists( 'Redux' ) ) {
        return;
    }

    if( class_exists( 'Redux' ) ) {
        $framework = new Redux();
    } else {
        $framework = new weLaunch();
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "wordpress_country_selector_options";

    // Set Continents
    if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/continents.php')) {
        require plugin_dir_path(dirname(__FILE__)).'data/continents.php';
    }

    // Set Countries
    if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countriesByContinents.php')) {
        require plugin_dir_path(dirname(__FILE__)).'data/countries.php';
    }

    
    $continentsOptions = array();
    $countriesOptions = array();

    // Continents
    foreach ($continents as $continent => $val) {
        $continentsOptions[] = array(
            'id'       => 'continent' . $continent,
            'type'     => 'checkbox',
            'title'    => __( 'Enable ' . $val, 'wordpress-country-selector' ),
            'default'  => '0',
        );
    }

    // Countries
    foreach($countries as $countryCode => $country) {
        $countriesOptions[] = array(
            'id'       => 'country' . $countryCode,
            'type'     => 'text',
            'title'    => __($country, 'wordpress-country-selector') . ' (' . $countryCode . ')',
            // 'validate' => 'url',
            'default'  => '',
            // 'required' => array('continent' . $continent,'equals','1'),
            );
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name' => 'wordpress_country_selector_options',
        'use_cdn' => TRUE,
        'dev_mode' => FALSE,
        'display_name' => 'Wordpress Country Selector',
        'display_version' => '1.6.3',
        'page_title' => 'WordPress Country Selector',
        'update_notice' => TRUE,
        'intro_text' => '',
        'footer_text' => '&copy; '.date('Y').' weLaunch',
        'admin_bar' => TRUE,
        'menu_type' => 'menu',
        'menu_title' => 'Country Selector',
        'menu_icon' => 'dashicons-flag',
        'allow_sub_menu' => TRUE,
        'page_parent' => 'options-general.php',
        'customizer' => FALSE,
        'default_mark' => '*',
        'hints' => array(
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'compiler' => TRUE,
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'network',
        'transient_time' => '3600',
        'network_sites' => TRUE,
        'network_admin' => true,
    );

    global $weLaunchLicenses;
    if( isset($weLaunchLicenses['wordpress-country-selector']) && !empty($weLaunchLicenses['wordpress-country-selector']) ) {
        $args['display_name'] = '<span class="dashicons dashicons-yes-alt" style="color: #9CCC65 !important;"></span> ' . $args['display_name'];
    } else {
        $args['display_name'] = '<span class="dashicons dashicons-dismiss" style="color: #EF5350 !important;"></span> ' . $args['display_name'];
    }

    $framework::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'help-tab',
            'title'   => __( 'Information', 'wordpress-country-selector' ),
            'content' => __( '<p>Need support? Please use the comment function on codecanyon.</p>', 'wordpress-country-selector' )
        ),
    );
    $framework::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    // $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'wordpress-country-selector' );
    // $framework::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    $framework::setSection( $opt_name, array(
        'title'  => __( 'Country Selector', 'wordpress-country-selector' ),
        'id'     => 'general',
        'desc'   => __( 'Need support? Please use the comment function on codecanyon.', 'wordpress-country-selector' ),
        'icon'   => 'el el-home',
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'General', 'wordpress-country-selector' ),
        'desc'       => __( 'To get auto updates please <a href="' . admin_url('tools.php?page=welaunch-framework') . '">register your License here</a>.', 'wordpress-country-selector' ),
        'id'         => 'general-settings',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enable',
                'type'     => 'checkbox',
                'title'    => __( 'Enable', 'wordpress-country-selector' ),
                'subtitle' => __( 'Enable the Country Selector.', 'wordpress-country-selector' ),
                'default'  => '0',
            ),
            array(
                'id'       => 'apiKey',
                'type'     => 'text',
                'title'    => __('IP Service API Key', 'wordpress-country-selector'),
                'subtitle' => __('By default 10.000 request are free each month, but when you exceed this limit you need to purchase a valid license key from <a href="https://extreme-ip-lookup.com/" target="_blank">Extreme IP lookup</a>.', 'wordpress-country-selector'),
                'default'  => '',
            ),
            array(
                'id'       => 'cookieLifetime',
                'type'     => 'spinner',
                'title'    => __( 'Cookie Lifetime.', 'wordpress-country-selector' ),
                'subtitle'    => __( 'Days before the Cookie expires.', 'wordpress-country-selector' ),
                'min'      => '0',
                'step'     => '1',
                'max'      => '9999',
                'default'  => '60',
            ),
            array(
                'id'       => 'showCountryDefault',
                'type'     => 'checkbox',
                'title'    => __( 'Show Default Country', 'wordpress-country-selector' ),
                'subtitle' => __( 'Show a Default Country if none of Users languages are covered. <br/>Otherwise the user will not get a Popup.', 'wordpress-country-selector' ),
                'default'  => '1',
            ),
            array(
                'id'       => 'countryDefaultURL',
                'type'     => 'text',
                'title'    => __('Default Country URL', 'wordpress-country-selector'),
                'subtitle' => __('This site will be used if the users locale is not covered!', 'wordpress-country-selector'),
                'default'  => 'http://www.yourwebsite.com/international/',
                'required' => array('showCountryDefault','equals','1'),
            ),
            array(
                'id'       => 'redirectOnCookie',
                'type'     => 'checkbox',
                'title'    => __( 'Redirect Default URL by Cookie', 'wordpress-country-selector' ),
                'subtitle' => __( 'If a user has choosen a country before and visits the default site, he will be redirected. Users still can access country URLs (other than the force redirect method).', 'wordpress-country-selector' ),
                'default'  => '0',
                'required' => array('showCountryDefault','equals','1'),
            ),

            array(
                'id'       => 'getSitesCountryByLanguage',
                'type'     => 'checkbox',
                'title'    => __( 'Get current Sites Country by Language', 'wordpress-country-selector' ),
                'subtitle' => __( 'If checked the current sites country will be taken from your language settings in wp-admin. Otherwise it will cross check URLs, which costs more performance.', 'wordpress-country-selector' ),
                'default'  => '1',
            ),
            
            array(
                'id'       => 'tryCorrectPage',
                'type'     => 'checkbox',
                'title'    => __( 'Link to current page', 'wordpress-country-selector' ),
                'subtitle' => __( 'User on site domain.com/test/ will see a link goes to domain.de/test/ for example. It is important, that you have the same URL names.', 'wordpress-country-selector' ),
                'default'  => '0',
            ),
            array(
                'id'       => 'forceRedirect',
                'type'     => 'checkbox',
                'title'    => __( 'Force Redirect', 'wordpress-country-selector' ),
                'subtitle' => __( 'Automatically Redirect the User.', 'wordpress-country-selector' ),
                'default'  => '0',
            ),
            array(
                'id'       => 'forceRedirectSeconds',
                'type'     => 'spinner',
                'title'    => __( 'Seconds when the redirect should happen.', 'wordpress-country-selector' ),
                'min'      => '0',
                'step'     => '1',
                'max'      => '9999',
                'default'  => '5',
                'required' => array('forceRedirect','equals','1'),
            ),
            array(
                'id'       => 'forceRedirectExcludeLoggedIn',
                'type'     => 'checkbox',
                'title'    => __( 'Exclude logged In', 'wordpress-country-selector' ),
                'subtitle' => __( 'Do not redirect logged in users', 'wordpress-country-selector' ),
                'default'  => '1',
                'required' => array('forceRedirect','equals','1'),
            ),
            array(
                'id'       => 'countryPageModal',
                'type'     => 'checkbox',
                'title'    => __( 'Country Page in Modal?', 'wordpress-country-selector' ),
                'subtitle' => __( 'Load country overview page in a Modal when somebody clicks on the widget link.', 'wordpress-country-selector' ),
                'default'  => '0',
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Continents', 'wordpress-country-selector' ),
        // 'desc'       => __( '', 'wordpress-country-selector' ),
        'id'         => 'conintents',
        'subsection' => true,
        'fields'     => $continentsOptions,
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Countries', 'wordpress-country-selector' ),
        // 'desc'       => __( '', 'wordpress-country-selector' ),
        'id'         => 'countries',
        'subsection' => true,
        'fields'     => $countriesOptions,
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Page', 'wordpress-country-selector' ),
        'id'         => 'page',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enablePage',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Page', 'wordpress-country-selector' ),
                'default'  => '1',
            ),
            array(
                'id'       => 'pageURL',
                'type'     => 'text',
                'title'    => __('Country Selector URL', 'wordpress-country-selector'),
                'subtitle' => __('Set your Country Selector URL.<br/>Remember to add the shortcode: [wordpress_country_selector]', 'wordpress-country-selector'),
                'validate' => 'url',
                'required' => array('enablePage','equals','1'),
            ),
            array(
                'id'       => 'pageShowContinents',
                'type'     => 'checkbox',
                'title'    => __( 'Show Continent Selector', 'wordpress-country-selector' ),
                'subtitle' => __('Show the Continet Maps with that you can filter.', 'wordpress-country-selector'),
                'default'  => '1',
                'required' => array('enablePage','equals','1'),
            ),
            array(
                'id'       => 'pageStyle',
                'type'     => 'select',
                'title'    => __('Page Style', 'wordpress-country-selector'), 
                'options'  => array(
                    'next' => 'Next to Each Other',
                    'list' => 'List View',
                    'dropdown' => 'Dropdown',
                ),
                'default'  => 'next',
            ),
            array(
                'id'       => 'pageDropdownNotice',
                'type'     => 'text',
                'title'    => __('Notice Text', 'wordpress-country-selector'),
                'default'  => __('Please choose a region and then a country to continue.', 'wordpress-country-selector'),
                'required' => array('pageStyle','equals','dropdown'),
                'args'     => array('teeny' => false),
            ),
            array(
                'id'       => 'pageDropdownButton',
                'type'     => 'text',
                'title'    => __('Button Text', 'wordpress-country-selector'),
                'default'  => __('Go to Website', 'wordpress-country-selector'),
                'required' => array('pageStyle','equals','dropdown'),
            ),
            array(
                'id'       => 'pageShowFlags',
                'type'     => 'checkbox',
                'title'    => __( 'Show Country Flags', 'wordpress-country-selector' ),
                'subtitle' => __('Show flags next to each country.', 'wordpress-country-selector'),
                'default'  => '1',
                'required' => array('enablePage','equals','1'),
            ),
            array(
                'id'       => 'pageFlagStyle',
                'type'     => 'select',
                'title'    => __('Flag Style', 'wordpress-country-selector'), 
                'options'  => array(
                    'simple' => 'Simple',
                    'circle' => 'Circle',
                ),
                'default'  => 'simple',
                'required' => array('pageShowFlags','equals','1'),
            ),
            array(
                'id'       => 'pageShowCountryMap',
                'type'     => 'checkbox',
                'title'    => __( 'Show Country Map', 'wordpress-country-selector' ),
                'subtitle' => __('Show a map next to each country.', 'wordpress-country-selector'),
                'default'  => '0',
                'required' => array('enablePage','equals','1'),
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Popup', 'wordpress-country-selector' ),
        'id'         => 'popup',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enablePopup',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Popup', 'wordpress-country-selector' ),
                'default'  => '1',
            ),
            array(
                'id'       => 'popupStyle',
                'type'     => 'select',
                'title'    => __('Popup Style', 'wordpress-country-selector'), 
                'subtitle' => __('The Popup Style', 'wordpress-country-selector'),
                'options'  => array(
                    'modal' => 'Modal',
                    'header' => 'Header',
                    'continue' => 'Continue & All Flags',
                ),
                'default'  => 'modal',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'     =>'popupTextColor',
                'type' => 'color',
                'url'      => true,
                'title' => __('Text Color', 'wordpress-country-selector'), 
                'validate' => 'color',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'     =>'popupBackgroundColor',
                'type' => 'color',
                'url'      => true,
                'title' => __('Background Color', 'wordpress-country-selector'), 
                'validate' => 'color',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'       => 'popupSize',
                'type'     => 'select',
                'title'    => __('Modal size', 'wordpress-country-selector'),
                'subtitle' => __('Size of the modal.', 'wordpress-country-selector'),
                'options'  => array(
                    'modal-normal' => __('Normal', 'wordpress-country-selector'),
                    'modal-sm' => __('Small', 'wordpress-country-selector'),
                    'modal-lg' => __('Large', 'wordpress-country-selector'),
                ),
                'default'  => 'modal-normal',
                'required' => array('popupStyle','equals','modal'),
            ),
            array(
                'id'       => 'popupAlwaysShow',
                'type'     => 'checkbox',
                'title'    => __( 'Always Show Popup', 'wordpress-country-selector' ),
                'subtitle'    => __( 'Show the Popup regardless if the country of the site is the same as the users country.', 'wordpress-country-selector' ),
                'default'  => '0',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'       => 'popupHeader',
                'type'     => 'checkbox',
                'title'    => __( 'Show Header', 'wordpress-country-selector' ),
                'default'  => '0',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'       => 'popupHeaderText',
                'type'     => 'text',
                'title'    => __('Custom Header Text', 'wordpress-country-selector'),
                'default'  => __('So you are from %s?', 'wordpress-country-selector'),
                'required' => array('popupHeader','equals','1'),
                'args'     => array('teeny' => false),
            ),
            array(
                'id'       => 'popupBody',
                'type'     => 'checkbox',
                'title'    => __( 'Show Body', 'wordpress-country-selector' ),
                'default'  => '1',
                'required' => array('enablePopup','equals','1'),
            ),
            array(
                'id'       => 'popupSeemsLikeText',
                'type'     => 'text',
                'title'    => __('Seems Like Text', 'wordpress-country-selector'),
                'default'  => __('<b>Seems like you are coming from %s!</b>', 'wordpress-country-selector'),
                'required' => array('popupBody','equals','1'),
            ),
            array(
                'id'       => 'popupInternationalText',
                'type'     => 'text',
                'title'    => __('Seems Like Text', 'wordpress-country-selector'),
                'default'  => __('Do you want to visit our international Website?', 'wordpress-country-selector'),
                'required' => array('popupBody','equals','1'),
            ),
            array(
                'id'       => 'popupCountryText',
                'type'     => 'text',
                'title'    => __('Country Text', 'wordpress-country-selector'),
                'default'  => __('Do you want to visit our Website in your country?', 'wordpress-country-selector'),
                'required' => array('popupBody','equals','1'),
            ),

            array(
                'id'       => 'popupRedirectText',
                'type'     => 'text',
                'title'    => __('Redirect Text', 'wordpress-country-selector'),
                'default'  => __('We will redirect you!', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1'), array('forceRedirect','equals','1')),
            ),
            array(
                'id'       => 'popupContinueText',
                'type'     => 'text',
                'title'    => __('Continue Text', 'wordpress-country-selector'),
                'default'  => __('Continue', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1'), array('popupStyle','equals','continue')),
            ),

            array(
                'id'       => 'popupVisitInternationalText',
                'type'     => 'text',
                'title'    => __('Visit International Text', 'wordpress-country-selector'),
                'default'  => __('Visit International', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1')),
            ),

            array(
                'id'       => 'popupGoToText',
                'type'     => 'text',
                'title'    => __('Got to %s Text', 'wordpress-country-selector'),
                'default'  => __('Go to %s', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1')),
            ),

            array(
                'id'       => 'popupStayInternationalText',
                'type'     => 'text',
                'title'    => __('Stay at International Text', 'wordpress-country-selector'),
                'default'  => __('Stay at International', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1')),
            ),
            array(
                'id'       => 'popupStayAtText',
                'type'     => 'text',
                'title'    => __('Stay at %s Text', 'wordpress-country-selector'),
                'default'  => __('Stay at %s', 'wordpress-country-selector'),
                'required' => array( array('popupBody','equals','1')),
            ),

            array(
                'id'       => 'popupFooter',
                'type'     => 'checkbox',
                'title'    => __( 'Show Footer', 'wordpress-country-selector' ),
                'default'  => '0',
                'required' => array('enablePopup','equals','1'),
            ),
        )
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Advanced settings', 'wordpress-country-selector' ),
        'desc'       => __( 'Custom stylesheet / javascript.', 'wordpress-country-selector' ),
        'id'         => 'advanced',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'customCSS',
                'type'     => 'ace_editor',
                'mode'     => 'css',
                'title'    => __( 'Custom CSS', 'wordpress-country-selector' ),
                'subtitle' => __( 'Add some stylesheet if you want.', 'wordpress-country-selector' ),
            ),
            // array(
            //     'id'       => 'removePathFromUsersURL',
            //     'type'     => 'checkbox',
            //     'title'    => __( 'Always remove the Path from Users URL', 'wordpress-country-selector' ),
            //     'default'  => 0,
            // ),
            array(
                'id'       => 'doNotLoadBootstrapCSS',
                'type'     => 'checkbox',
                'title'    => __( 'Do Not load Bootstrap CSS', 'wordpress-country-selector' ),
                'default'  => 0,
            ),
            array(
                'id'       => 'doNotLoadBootstrapJS',
                'type'     => 'checkbox',
                'title'    => __( 'Do Not load Bootstrap JS', 'wordpress-country-selector' ),
                'default'  => 0,
            ),
        )
    ));
    /*
     * <--- END SECTIONS
     */
