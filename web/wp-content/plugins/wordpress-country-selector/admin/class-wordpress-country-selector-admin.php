<?php

class Wordpress_Country_Selector_Admin
{
    private $plugin_name;
    private $version;

    /**
     * Construct Country Selector Admin Class
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   string                         $plugin_name
     * @param   string                         $version    
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Load Extensions
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  boolean
     */
    public function load_extensions()
    {
        // Load the theme/plugin options
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'admin/options-init.php')) {
            require_once plugin_dir_path(dirname(__FILE__)).'admin/options-init.php';
        }

    }

    /**
     * Get Options
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   mixed                         $option The option key
     * @return  mixed                                 The option value
     */
    private function get_option($option)
    {
        if(!is_array($this->options)) {
            return false;
        }

        if (!array_key_exists($option, $this->options)) {
            return false;
        }

        return $this->options[$option];
    }

    /**
     * Init Admin
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                         [description]
     */
    public function init()
    {
        global $wordpress_country_selector_options, $locale;

        $this->options = $wordpress_country_selector_options;

        if (!$this->get_option('enable')) {
            return false;
        }

        add_shortcode( 'country_selector', array($this, 'add_shortcode'));

        return true;
    }

    public function register_widgets() {

        register_widget( 'Wordpress_Country_Selector_Widget' );

    }

    public function add_shortcode($atts)
    {
        global $wordpress_country_selector_options;

        $attributes = extract( shortcode_atts( array(
            'style' => 'link',
            'text' => 'Switch Country'
        ), $atts ) );

        // $currentCountry = $this->get_sites_country();
        $activeCountries = array_filter($wordpress_country_selector_options,  array($this, 'filter_countries'), ARRAY_FILTER_USE_KEY);
        $activeCountries = array_filter($activeCountries);

        if ( function_exists('icl_object_id') ) {  
            
            if(isset($_COOKIE['wpml_referer_url']) && !empty($_COOKIE['wpml_referer_url'])) {
                $users_url = parse_url( urldecode( $_COOKIE['wpml_referer_url']) );
            } else {
                $users_url = parse_url( $_SERVER['HTTP_REFERER'] );
            }
            
            $path = explode('/', $users_url['path']);

            if(isset($path[1]) && ( strlen( $path[1]) === 2) ) {
                $users_url = $users_url["scheme"] . "://" . $users_url["host"] . '/' . $path[1] . '/';
            } else {
                $users_url = $users_url["scheme"] . "://" . $users_url["host"];
            }

        } else {

            $users_url = parse_url( get_site_url() );
            $path = array();
            if(isset($users_url['path'])) {
                $path = explode('/', $users_url['path']);
            }
            
            if(isset($path[1])) {
                $users_url = $users_url["scheme"] . "://" . $users_url["host"] . '/' . $path[1] . '/';
            } else {
                $users_url = $users_url["scheme"] . "://" . $users_url["host"];
            }
        }

        $currentCountry = str_replace('country', '', array_search($users_url, $activeCountries));

        if(!isset($activeCountries['country' . $currentCountry]) || empty($activeCountries['country' . $currentCountry])) {
            $currentCountry = 'UN';
        }

        // Set Countries
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countries.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/countries.php';
        }
        
        $text = sprintf($text, $countries[$currentCountry]);

        $html = "";
        // Dropdown
        if($style == "dropdown") {

            $html .=    '<div class="country_selector_dropdown">
                                <a href="#" class="country_selector_dropbtn"><span class="flag-icon flag-icon-' . strtolower($currentCountry) . '"></span> ' . $text . '</a>
                                <div class="country_selector_dropdown-content">';

                                foreach ($activeCountries as $countryCode => $countryURL) {
                                    $countryCode = substr($countryCode, 7, 9);

                                    if($this->get_option('tryCorrectPage')) {
                                        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
                                                    "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        $countryURL = $countryURL . '/' . str_replace($users_url, '', $current_url);
                                    }

                                    $html .= '<a href="' . $countryURL . '" data-country-code="' . $countryCode . '">
                                              <span class="flag-icon flag-icon-' . strtolower( $countryCode ) . '"></span> ' . $countries[$countryCode] . '</a>';
                                }
                                if($this->get_option('showCountryDefault') && !empty($this->get_option('countryDefaultURL'))) {
                                    $html .= '<a href="' . $this->get_option('countryDefaultURL') . '">';
                                        $html .= '<span class="flag-icon flag-icon-un"></span>' . __('International', 'wordpress-country-selector');
                                    $html .= '</a>';
                                }

            $html .=            '</div>
                            </div>';
        // Simple Link
        } else {
            $html .= '<a id="country_selector_modal_page_show" href="' . $wordpress_country_selector_options['pageURL'] . '?redirect-false"><span class="flag-icon flag-icon-' . strtolower($currentCountry) . '"></span> ' . $text . '</a>';
        }

        return $html;
    }

    private function filter_countries($key) 
    {
        return substr($key, 0, 7) === 'country' && strlen($key) === 9;
    }

    /**
     * Get Sites Country
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_sites_country()
    {
        $locale = get_bloginfo('language');

        if(strlen($locale) == 5) {
            $country = substr($locale, 3);
            $country = strtoupper($country);
            return $country;
        }

        if(strlen($locale) == 2) {
            $country = strtoupper($locale);
            return $country;
        }

        return false;
    }
}