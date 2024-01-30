<?php

class Wordpress_Country_Selector_Public
{
    private $plugin_name;
    private $version;
    private $options;

    /**
     * Country Selector Plugin Construct
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
     * Enqueue Styles
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  boolean
     */
    public function enqueue_styles()
    {
        global $wordpress_country_selector_options;

        $this->options = $wordpress_country_selector_options;

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'css/wordpress-country-selector-public.css', array(), $this->version, 'all');
        $doNotLoadBootstrap = $this->get_option('doNotLoadBootstrapCSS');
        if (!$doNotLoadBootstrap) {
            wp_enqueue_style($this->plugin_name.'-bootstrap', plugin_dir_url(__FILE__).'css/bootstrap.min.css', array(), $this->version, 'all');
        }
        wp_enqueue_style($this->plugin_name.'-mapglyphs', plugin_dir_url(__FILE__).'css/mapglyphs.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-flag-icon', plugin_dir_url(__FILE__).'css/flag-icon.min.css', array(), $this->version, 'all');
        wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0', 'all');

        $css = "";
        if (!$this->get_option('pageShowFlags')) {
            $css .= '.country_selector_country .flag-icon{display:none !important;}';
        }
        if (!$this->get_option('pageShowCountryMap')) {
            $css .= '.country_selector_country .mg{display:none !important;}';
        }


        $customCSS = $this->get_option('customCSS');
        file_put_contents(__DIR__.'/css/wordpress-country-selector-custom.css', $css.$customCSS);

        wp_enqueue_style($this->plugin_name.'-custom', plugin_dir_url(__FILE__).'css/wordpress-country-selector-custom.css', array(), $this->version, 'all');

        return true;
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  boolean
     */
    public function enqueue_scripts()
    {
        global $wordpress_country_selector_options;

        $this->options = $wordpress_country_selector_options;

        $doNotLoadBootstrap = $this->get_option('doNotLoadBootstrapJS');
        if (!$doNotLoadBootstrap) {
            wp_enqueue_script($this->plugin_name.'-bootstrap', plugin_dir_url(__FILE__).'js/bootstrap.min.js', array('jquery'), $this->version, true);
        }

        wp_enqueue_script($this->plugin_name.'-public', plugin_dir_url(__FILE__).'js/wordpress-country-selector-public.js', array('jquery'), $this->version, true);

        $forJS = array( 
            'cookie_lifetime' => $wordpress_country_selector_options['cookieLifetime'],
            'redirectOnCookie' => $wordpress_country_selector_options['redirectOnCookie'],
            'apiKey' => $wordpress_country_selector_options['apiKey'],
            'ajax_url' => admin_url('admin-ajax.php'),
        );
        wp_localize_script($this->plugin_name.'-public', 'country_selector_options', $forJS);

        return true;
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

        // Set Continents
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/continents.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/continents.php';

            $this->continents = $continents;
        }

        // Set Countries BY Continents
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countriesByContinents.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/countriesByContinents.php';

            $this->countriesByContinents = $countriesByContinents;
        }

        // Set Countries
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countries.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/countries.php';

            $this->countries = $countries;
        }

        add_action('wp_footer', array($this, 'get_popup'), 40);   

        if($this->get_option('countryPageModal')) {
            add_action('wp_footer', array($this, 'get_popup_country_page'), 50);
        }

        add_shortcode('wordpress_country_selector', array($this, 'get_page'));

        $forceRedirect = $this->get_option('forceRedirect');
        $seconds = $this->get_option('forceRedirectSeconds');
        
        if( ($forceRedirect) && ($seconds == 0) ){

            global $_COOKIE;

            $is_bot = $this->detect_bots();
            if($is_bot) {
                return;
            }

            $this->sites_country = $this->get_sites_country();
            $this->sites_URL = get_site_url();

            $countryCode = "";
            if (isset($_COOKIE['country_selector_country_code']) && !empty($_COOKIE['country_selector_country_code'])) {

                $countryCode = $_COOKIE['country_selector_country_code'];

            } else {
                $ip = $_SERVER['REMOTE_ADDR'];

                $url = "https://extreme-ip-lookup.com/json/";
                if($this->get_option('apiKey')) {
                    $url .= "?key=" . $this->get_option('apiKey');
                }

                $geolocate = json_decode(file_get_contents($url));

                if(isset($geolocate->countryCode)) {
                    $countryCode = $geolocate->countryCode;
                }
            }


            $this->users_country = $countryCode;
            $this->users_locale = $this->get_users_locale();     
            $this->target_URL = $this->get_target_URL();

            setcookie("country_selector_country_code", $countryCode, $this->get_option('cookieLifetime') * 24 * 60 * 60 * 1000);  /* expire in 1 hour */            

            if(!empty($this->target_URL) && ($this->sites_URL != $this->target_URL)) {
                add_action('wp', array($this, 'get_redirect_wp')); 
            }
        }
    }


   /**
     * Create the country selector Modal
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup()
    {
        $popupSize = $this->get_option('popupSize');
        $enablePopup = $this->get_option('enablePopup');
        $popupStyle = $this->get_option('popupStyle');
        if(!$enablePopup) return false; 
        ?>

        <!-- Wordpress Country Selector Modal -->
        <div id="country_selector_modal" class="country_selector_modal wordpress-country-selector-modal fade country_selector_modal_<?php echo $popupStyle ?>" tabindex="-1" role="dialog">
            <div class="wordpress-country-selector-modal-dialog <?php echo $popupSize ?>" role="document">
                <div class="wordpress-country-selector-modal-content">
                    <?php $this->get_popup_header() ?>
                    <?php $this->get_popup_body() ?>
                    <?php $this->get_popup_footer() ?>
                    <button type="button" class="country_selector_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Get Popup Header
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_header()
    {
        $popupHeader = $this->get_option('popupHeader');
        if(!$popupHeader) return false;
        ?>

        <div class="wordpress-country-selector-modal-header">
            <button type="button" class="country_selector_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="wordpress-country-selector-modal-title"></h4>
        </div>
    <?php
    }

    /**
     * Get Popup Body
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_body()
    {
        $popupBody = $this->get_option('popupBody');
        if(!$popupBody) return false;
        $popupTextColor = $this->get_option('popupTextColor');
        $popupBackgroundColor = $this->get_option('popupBackgroundColor');
        ?>

        <div class="wordpress-country-selector-modal-body" style="color: <?php echo $popupTextColor ?>; background-color: <?php echo $popupBackgroundColor ?>;">
            <div class="country_selector_modal_text">
                
            </div>
            <div class="country_selector_modal_buttons">
               
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
    }

    /**
     * Get Popup Footer
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_footer()
    {
        $popupFooter = $this->get_option('popupFooter');
        if(!$popupFooter) return false;
        ?>

        <div class="wordpress-country-selector-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    <?php
    }

    /**
     * Get Country Selector Page
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_page()
    {
        $enablePage = $this->get_option('enablePage');
        $pageStyle = $this->get_option('pageStyle');
        $continents  = $this->get_continents();
        
        if(!$enablePage) return false;

        ob_start();
        ?>
        <div id="country_selector_page" class="country_selector_page">

            <div class="country_selector_countries"> 
                <?php
                if($pageStyle == "list") {
                    $this->get_page_continents();
                    $this->get_page_countries_list($continents);
                } elseif($pageStyle == "dropdown") {
                    $this->get_page_continents_dropdown();
                    $this->get_page_countries_dropdown($continents);
                    $this->get_page_notice_dropdown();
                    $this->get_page_button_dropdown();
                } else {
                    $this->get_page_continents();
                    $this->get_page_countries_next($continents);
                }

                if($this->get_option('showCountryDefault') && !empty($this->get_option('countryDefaultURL'))) {
                ?>
                <div class="wordpress-country-selector-row">
                    <hr>
                    <div class="wordpress-country-selector-col-sm-12 country_selector_country text-center">
                        <a href="<?= $this->get_option('countryDefaultURL') ?>" class="country_selector_international">
                            <span class="flag-icon flag-icon-un"></span>
                            <?= $this->get_option('popupVisitInternationalText') ?>
                        </a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>

        <?php
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }

    /**
     * Get Country Selectors Page Continents
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_page_continents()
    {
        $continents  = $this->get_continents();

        if(!$this->get_option('pageShowContinents')) {
            return false;
        }
        ?>
            <div class="country_selector_continents">
                <div class="wordpress-country-selector-row">
                <?php
                    $first = true;
                    foreach ($continents as $continent => $continentName) {
                        $continentSet = $this->get_option('continent' . $continent);

                        if(!$continentSet) {
                            unset($continents[$continent]);
                            continue;
                        }

                        $css = '';
                        if ($first == true){
                            $css = 'wordpress-country-selector-col-sm-offset-1';
                            $first = false;
                        }
                ?>
                    <div class="wordpress-country-selector-col-sm-2 <?php echo $css ?>">
                        <a href="#" data-continent="<?php echo strtolower($continent) ?>" class="country_selector_continent">
                            <h2><?php echo __($continent, 'wordpress-country-selector') ?></h2>
                            <i class="mg map-wrld-<?php echo substr(strtolower($continent), 0, 2) ?>"></i>
                        </a>
                    </div>
                <?php
                    }
                ?>
                </div>
            </div>
        <?php
    }

    /**
     * Get Country Selectors Page Continents
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.12.3
     * @link    https://plugins.db-dzine.com
     */
    public function get_page_continents_dropdown()
    {
        $continents  = $this->get_continents();

        if(!$this->get_option('pageShowContinents')) {
            return false;
        }
        ?>
            <div class="country_selector_continents">
                <div id="country_selector_continents_dropdown" class="country_selector_continents_dropdown">
                    <select name="country_selector_continents_dropdown">
                        <option value=""><?php echo __('Select Region', 'wordpress-country-selector') ?></option>
                        <?php
                        foreach ($continents as $continent => $continentName) {
                            $continentSet = $this->get_option('continent' . $continent);

                            if(!$continentSet) {
                                unset($continents[$continent]);
                                continue;
                            }
                            ?>

                            <option value="<?php echo strtolower($continent) ?>"><?php echo __($continent, 'wordpress-country-selector') ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="country_selector_continents_dropdown_arrow"></div>
                </div>
            </div>
        <?php
    }

    /**
     * Get Country Selectors Page Countries
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_page_countries()
    {
        $continents  = $this->get_continents();
        $pageStyle = $this->get_option('pageStyle');
        ?>
            <div class="country_selector_countries"> 

            <?php
            if($pageStyle == "list") {
                $this->get_page_countries_list($continents);
            } else {
                $this->get_page_countries_next($continents);
            }

            ?>
            </div>
        <?php
    }

    private function get_page_countries_list($continents)
    {
        $pageFlagStyle = $this->get_option('pageFlagStyle');

        echo '<div class="wordpress-country-selector-row country_selector_countries_by_continent">';

        // Continents
        $first = true;
        foreach ($continents as $continent => $continentName) {

            $continentSet = $this->get_option('continent' . $continent);
            if(!$continentSet) {
                unset($continents[$continent]);
                continue;
            }

            $css = '';
            if ($first == true){
                $css = 'col-sm-offset-1';
                $first = false;
            }
            
            echo '<div class="wordpress-country-selector-col-sm-2 country_selector_countries_' . strtolower($continent) . ' ' . $css . '">';
                echo '<h3>' . __($continent, 'wordpress-country-selector') . '</h3>';
                echo '<ul class="country_selector_countries">';

            // Countries
            foreach($this->get_countries_by_continent($continent) as $countryCode => $country) {

                $countrySet = $this->get_option('country' . $countryCode);
                if(!$countrySet) {
                    continue;
                }
                
                echo '<li>
                        <a href="' . $countrySet .'" class="country_selector_country">' .
                            '<i class="mg map-' . strtolower($countryCode) . '"></i>' .
                            '<span class="flag-icon country_selector_page_flag flag-icon-' . strtolower($countryCode) . ' ' . $pageFlagStyle . '"></span>' . __($country, 'wordpress-country-selector') .
                        '</a>
                    </li>
                ';
                     
            }
            echo '</ul>
            </div>';
        }

        echo '</div>';
    }

    private function get_page_countries_dropdown($continents)
    {
        $pageFlagStyle = $this->get_option('pageFlagStyle');

        // Continents
        foreach ($continents as $continent => $continentName) {

            $continentSet = $this->get_option('continent' . $continent);
            if(!$continentSet) {
                unset($continents[$continent]);
                continue;
            }

            echo '<div id="country_selector_countries_dropdown_' . strtolower($continent) . '" class="country_selector_countries_dropdown" style="display:none;">';
                echo '<select name="country_selector_countries_dropdown">';

                    echo '<option value="">' . __('Select Country', 'wordpress-country-selector') . '</option>';
                    // Countries
                    foreach($this->get_countries_by_continent($continent) as $countryCode => $country) {

                        $countrySet = $this->get_option('country' . $countryCode);
                        if(!$countrySet) {
                            continue;
                        }
                        echo '<option value="' . strtolower($countrySet) . '">' . __($country, 'wordpress-country-selector') . '</option>';

                    }

                echo '</select>';
                echo '<div class="country_selector_countries_dropdown_arrow"></div>';
            echo '</div>';
        }

    }

    private function get_page_notice_dropdown()
    {
        $pageDropdownNotice = $this->get_option('pageDropdownNotice');

        if(empty($pageDropdownNotice)) {
            return false;
        }
        ?>
        <div id="country_selector_dropdown_notice" class="country_selector_dropdown_notice">
            <?php echo $pageDropdownNotice ?>
        </div>

        <?php
    }

    private function get_page_button_dropdown()
    {
        $pageDropdownButton = $this->get_option('pageDropdownButton');

        if(empty($pageDropdownButton)) {
            return false;
        }
        ?>
        <div id="country_selector_dropdown_button_container" class="country_selector_dropdown_button_container">
            <a href="#" id="country_selector_dropdown_button" class="btn btn-default button theme-button disabled country_selector_dropdown_button">
                <?php echo $pageDropdownButton ?>
            </a>
        </div>

        <?php
    }

    private function get_page_countries_next($continents)
    {
        $pageFlagStyle = $this->get_option('pageFlagStyle');

        // Continents
        foreach ($continents as $continent => $continentName) {

            $continentSet = $this->get_option('continent' . $continent);
            if(!$continentSet) {
                unset($continents[$continent]);
                continue;
            }

            echo '<div class="wordpress-country-selector-row country_selector_countries_by_continent country_selector_countries_' . strtolower($continent) . '">';
                echo '<div class="wordpress-country-selector-col-sm-12">';
                    echo '<h3>' . __($continent, 'wordpress-country-selector') . '</h3>';
                echo '</div>';

                // Countries
                foreach($this->get_countries_by_continent($continent) as $countryCode => $country) {

                    $countrySet = $this->get_option('country' . $countryCode);
                    if(!$countrySet) {
                        continue;
                    }

                    echo '<div class="wordpress-country-selector-col-sm-3 country_selector_country">
                            <a href="' . $countrySet .'">' .
                                '<i class="mg map-' . strtolower($countryCode) . '"></i>' . 
                                '<span class="flag-icon country_selector_page_flag flag-icon-' . strtolower($countryCode) . ' ' . $pageFlagStyle . '"></span>' . __($country, 'wordpress-country-selector') .
                            '</a>
                         </div>';
                }

            echo '</div>';
        }
    }

    /**
     * Get Countries by Continent
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    private function get_countries_by_continent($continent)
    {
        return $this->countriesByContinents[$continent];
    }

    /**
     * Get Continents
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    private function get_continents()
    {
        return $this->continents;
    }

    /**
     * Get User's Locale
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_users_locale()
    {
        $users_lang = 'en_US';

        $languages = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);

        foreach($languages as $language)
        {
            $lang = explode(';', $language);
            $lang = $lang[0];

            if(strlen($lang) == 5) {
                // WHY THE F*CK does the browser output a locale with a hyphen
                $users_lang = str_replace('-', '_', $lang);
                break;
            }
        }
        return $users_lang;
    }

    /**
     * Get Site's Country
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_sites_country()
    {

        if($this->get_option('getSitesCountryByLanguage')) {
            $locale = get_bloginfo('language');
            if(strlen($locale) == 5) {
                $country = substr($locale, 3);
                return $country;
            }

            if(strlen($locale) == 2) {
                $country = strtoupper($locale);
                return $country;
            }
        } else {

            $siteURL = get_site_url();            
            if($siteURL == $this->get_option('countryDefaultURL')) {
                return 'int';
            }

            foreach ($this->countries as $countryCode => $countryName) {
                $urlExists = $this->get_option('country' . $countryCode);
                if($urlExists && $urlExists == $siteURL) {
                    return $countryCode;
                }
            }
        }

        return false;
    }

    /**
     * Get Target URL
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_target_URL()
    {
        $targetURL = $this->get_option('country' . $this->users_country);

        if(isset($targetURL) && !empty($targetURL)) {
            return $targetURL;
        }

        if($this->get_option('showCountryDefault')) {
            return $this->get_option('countryDefaultURL');
        }

        return false;
    }

    /**
     * Detect Bots
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    private function detect_bots()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex|crawl|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Create the country selector page Modal
     * @author Daniel Barenkamp
     * @version 1.10.6
     * @since   1.10.6
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_country_page()
    {
        $popupSize = $this->get_option('popupSize');
        $enablePopup = $this->get_option('enablePopup');
        $popupStyle = $this->get_option('popupStyle');
        if(!$enablePopup) return false; 
        ?>

        <!-- Wordpress Country Selector Modal -->
        <div id="country_selector_modal_page" class="country_selector_modal wordpress-country-selector-modal fade country_selector_modal_<?php echo $popupStyle ?>" tabindex="-1" role="dialog">
            <div class="wordpress-country-selector-modal-dialog <?php echo $popupSize ?>" role="document">
                <div class="wordpress-country-selector-modal-content">
                    <?php $this->get_popup_country_page_header() ?>
                    <?php $this->get_popup_country_page_body() ?>
                    <?php $this->get_popup_country_page_footer() ?>
                    <button type="button" class="country_selector_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Get Popup Header
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_country_page_header()
    {
        $popupHeader = $this->get_option('popupHeader');
        if(!$popupHeader) return false;
        $popupHeaderText = $this->get_option('popupHeaderText');
        $usersCountry = $this->users_country;
        ?>

        <div class="wordpress-country-selector-modal-header">
            <button type="button" class="country_selector_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="wordpress-country-selector-modal-title"><?php echo sprintf($popupHeaderText, $this->countries[$usersCountry]) ?></h4>
        </div>
    <?php
    }

    /**
     * Get Redirect
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_redirect_wp()
    {
        $excludeLoggedIn = $this->get_option('forceRedirectExcludeLoggedIn');
        $targetURL = $this->target_URL;

        if(is_admin()){
            return false;
        }

        if($excludeLoggedIn) {
            if(is_user_logged_in()) {
                return false;
            }
        }
        
        wp_redirect( $targetURL );
        exit;
    }

    /**
     * Get Popup Footer
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_country_page_footer()
    {
        $popupFooter = $this->get_option('popupFooter');
        if(!$popupFooter) return false;
        ?>

        <div class="wordpress-country-selector-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    <?php
    }

/**
     * Get Popup Body
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function get_popup_country_page_body()
    {
        $popupBody = $this->get_option('popupBody');
        if(!$popupBody) return false;
        $popupTextColor = $this->get_option('popupTextColor');
        $popupBackgroundColor = $this->get_option('popupBackgroundColor');

        ?>

        <div class="wordpress-country-selector-modal-body" style="color: <?php echo $popupTextColor ?>; background-color: <?php echo $popupBackgroundColor ?>;">
           <?php 
           $this->get_page_countries();
           
            if($this->get_option('showCountryDefault') && !empty($this->get_option('countryDefaultURL'))) {
            ?>
            <div class="wordpress-country-selector-row">
                <hr>
                <div class="wordpress-country-selector-col-sm-12 country_selector_country text-center">
                    <a href="<?= $this->get_option('countryDefaultURL') ?>" class="country_selector_international">
                        <span class="flag-icon flag-icon-un"></span>
                        <?= $this->get_option('popupVisitInternationalText') ?>
                    </a>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        <?php
    }

    /**
     * Get Popup Body
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     */
    public function check_country_selector()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            die('No AJAX call!');
        }

        $isDefault = false;

        if ( function_exists('icl_object_id') ) {  
            
            if(isset($_COOKIE['wpml_referer_url']) && !empty($_COOKIE['wpml_referer_url'])) {
                $users_url = parse_url( urldecode( $_COOKIE['wpml_referer_url']) );
            } else {
                $users_url = parse_url( $_SERVER['HTTP_REFERER'] );
            }
            
            $path = explode('/', $users_url['path']);

            if(isset($path[1]) && ( strlen( $path[1]) === 2) ) {
                $this->users_url = $users_url["scheme"] . "://" . $users_url["host"] . '/' . $path[1] . '/';
            } else {
                $this->users_url = $users_url["scheme"] . "://" . $users_url["host"];
            }

        } else {
            $site_url = get_site_url();
            $users_url = parse_url( $site_url );
            $path = array();
            if(isset($users_url['path'])) {
                $path = explode('/', $users_url['path']);
            }

            if(isset($path[1])) {
                $this->users_url = $site_url . '/';
            } else {
                $this->users_url = $users_url["scheme"] . "://" . $users_url["host"];
            }
        }

        $this->users_country = $_POST['country'];
        $this->users_language = $_POST['lang'];
        $this->sites_country = $this->get_sites_country(); // $_POST['sites_locale'];

        // if(strlen($this->sites_locale) == 5) {
        //     $this->sites_country = substr($this->sites_locale, 3);
        // }

        // if(strlen($this->sites_locale) == 2) {
        //     $this->sites_country = strtoupper($this->sites_locale);
        // }

        // Set Countries
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countries.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/countries.php';

            $this->countries = $countries;
        }

        $this->target_URL = $this->get_target_URL();


        $full_translation_file = WP_PLUGIN_DIR . '/' . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/wordpress-country-selector-' . strtolower($this->users_language) . '_' . strtoupper($this->users_country)  . '.mo';
        $lang_lang_translation_file = WP_PLUGIN_DIR . '/' . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/wordpress-country-selector-' . strtolower($this->users_language) . '_' . strtoupper($this->users_language)  . '.mo';

        if( file_exists($full_translation_file)) {
            load_textdomain( 'wordpress-country-selector', $full_translation_file);
        } elseif(file_exists($lang_lang_translation_file)) {
            load_textdomain( 'wordpress-country-selector', $lang_lang_translation_file);
        }
        
        
        $forceRedirect = $this->get_option('forceRedirect');
        $forceRedirectSeconds = $this->get_option('forceRedirectSeconds');
        $excludeLoggedIn = $this->get_option('forceRedirectExcludeLoggedIn');

        $loggedIn = "0";
        if(is_user_logged_in()){
            $loggedIn = "1";
        }

        $popupHeaderText = $this->get_option('popupHeaderText');
        $popupHeaderText = sprintf($popupHeaderText, $this->countries[$this->users_country]);

        $showDefault = false;
        if($this->target_URL == $this->get_option('countryDefaultURL')) {
            $showDefault = true;
        }
        
        $showPopup = "1";
        if(empty($this->target_URL)) {
            $showPopup = "0";
        } else {
            if ($this->users_url === $this->target_URL) {
                $showPopup = "0";
            }                             
        }

        if($this->get_option('tryCorrectPage')) {
            $referrer = $_SERVER['HTTP_REFERER'];
            $this->target_URL = $this->target_URL . '/' . ltrim(str_replace($this->users_url, '', $referrer), '/');
        }

        $popupSeemsLikeText = $this->get_option('popupSeemsLikeText') ? __($this->get_option('popupSeemsLikeText'), 'wordpress-country-selector') : __('Seems like you are coming from %s!', 'wordpress-country-selector');
        $popupInternationalText = $this->get_option('popupInternationalText') ? __($this->get_option('popupInternationalText'), 'wordpress-country-selector') : __('Do you want to visit our international Website?', 'wordpress-country-selector');
        $popupCountryText = $this->get_option('popupCountryText') ? __($this->get_option('popupCountryText'), 'wordpress-country-selector') : __('Do you want to visit our Website in your country?', 'wordpress-country-selector');
        $popupRedirectText = $this->get_option('popupRedirectText') ? __($this->get_option('popupRedirectText'), 'wordpress-country-selector') : __('We will redirect you!', 'wordpress-country-selector');

        $popupModalText = '<p class="country_selector_seems_text">' . sprintf($popupSeemsLikeText, $this->countries[$this->users_country]) . '</p>';
        $popupStyle = $this->get_option('popupStyle');

        if($popupStyle !== "continue") {
            if($forceRedirect){
                $popupModalText .= '<p class="country_selector_redirect_text">' . $popupRedirectText . '</p>';
            } else {
                if($showDefault == true) {
                    $popupModalText .= '<p class="country_selector_international_text">' . $popupInternationalText . '</p>';
                } else {
                    $popupModalText .= '<p class="country_selector_country_text">' . $popupCountryText . '</p>';
                }
            }
        }
        
        if($popupStyle == "continue") {
            $popupButtonText = 
                    '<p class="country_selector_modal_flag_buttons">
                        <a href="#" class="country_selector_modal_stay">
                            ' . $this->get_option('popupContinueText') . '
                        </a>
                    </p>';
            $available_countries = $this->get_available_countries();
            if(!empty($available_countries)) {
                $popupButtonText .= '<p class="country_selector_country_text">' . $popupCountryText . '</p>';
                $popupButtonText .= '<p class="country_selector_modal_flags">';
                foreach ($available_countries as $countryCode => $countryURL) {
                    if($this->get_option('tryCorrectPage')) {
                        $referrer = $_SERVER['HTTP_REFERER'];

                        $countryURL = $countryURL . '/' . ltrim(str_replace($this->users_url, '', $referrer), '/');
                    }

                    $popupButtonText .= 
                        '<a href="' . $countryURL . '" class="country_selector_modal_flag">
                            <span class="flag-icon flag-icon-' . strtolower($countryCode) . '"></span>
                        </a>';
                }
                $popupButtonText .= '</p>';
            }

        } else {

            if($showDefault == true) {

                $popupButtonText = 
                    '<p class="country_selector_modal_flag_buttons">
                        <a href="' . $this->target_URL . '" class="country_selector_modal_goto">
                            ' . $this->get_option('popupVisitInternationalText') . '
                        </a>
                    </p>';

            } else {

                $popupButtonText = 
                    '<p class="country_selector_modal_flag_buttons">
                        <a href="' . $this->target_URL . '" class="country_selector_modal_goto">
                            <span class="flag-icon flag-icon-' . strtolower($this->users_country) . '"></span>
                            ' . sprintf( $this->get_option('popupGoToText'), $this->countries[$this->users_country]) . '
                        </a>
                    </p>';
            }

            if($this->users_url == $this->get_option('countryDefaultURL')) {
                $isDefault = true;
                $popupButtonText .= 
                    '<p class="country_selector_modal_flag_buttons">
                        <a href="' . $this->users_url . '" class="country_selector_modal_stay" data-dismiss="modal">
                            <span class="flag-icon flag-icon-un"></span>
                            ' . $this->get_option('popupStayInternationalText') . '
                        </a>
                    </p>';
            } else {
                $popupButtonText .= 
                    '<p class="country_selector_modal_flag_buttons">
                        <a href="' . $this->users_url . '" class="country_selector_modal_stay" data-dismiss="modal">
                            <span class="flag-icon flag-icon-' . strtolower($this->sites_country) . '"></span>
                            ' . sprintf( $this->get_option('popupStayAtText'), $this->countries[$this->sites_country]) . '
                        </a>
                    </p>';
            }
        }

        if( ($this->get_option('popupAlwaysShow') === "1") && !$forceRedirect) {
            $showPopup = "1";
        }

        if( ($forceRedirect) && ($forceRedirectSeconds == 0) ){
            $showPopup = "0";
        }

        $return = array(
            'users_url' => $this->users_url,
            'users_country' => $this->users_country,
            'users_language' => $this->users_language,
            'target_URL' => $this->target_URL,
            'force_redirect' => $forceRedirect,
            'force_redirect_seconds' => $forceRedirectSeconds,
            'force_redirect_exclude_logged_in' => $excludeLoggedIn,
            'logged_in' => $loggedIn,
            'modal_header' => $popupHeaderText,
            'modal_text' => $popupModalText,
            'modal_buttons' => $popupButtonText,
            'show_popup' => $showPopup,
            'is_default' => $isDefault,
        );

        die(json_encode($return));
    }

    private function get_available_countries()
    {
        $available_countries = get_transient( 'wordpress_country_selector_available_countries' );
        $available_countries = apply_filters('wordpress_country_selector_available_countries_filter', $available_countries);

        if(!empty($available_countries)) {
            return $available_countries;
        }

        $available_countries = array();
        foreach ($this->countries as $countryCode => $country) {
            $available_country = $this->get_option('country' . $countryCode);
            if(!$available_country) {
                continue;
            }
            $available_countries[$countryCode] = $available_country; // __($country, 'wordpress-country-selector');
        }

        $transient_expiration = apply_filters('wordpress_country_selector_transient_expiration', 3600);
        $available_countries = apply_filters('wordpress_country_selector_available_countries_filter', $available_countries);
        
        set_transient( 'wordpress_country_selector_available_countries', $available_countries, $transient_expiration);

        return $available_countries;
    }
}