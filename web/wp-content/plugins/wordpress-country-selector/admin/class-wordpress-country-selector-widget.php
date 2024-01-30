<?php

class Wordpress_Country_Selector_Widget extends WP_Widget
{
	/**
	 * Register Country Selector Widget
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    https://plugins.db-dzine.com
	 */
    public function __construct()
    {
        $options = array( 
			'classname' => 'wordpress_country_selector',
			'description' => __('This will show the current locale with a flag and a link to the country selector page.', 'wordpress-country-selector'),
		);

        parent::__construct('wordpress_country_selector', __('Country Selector', 'wordpress-country-selector'), $options);
    }

    /**
     * Widget output
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                         $args     [description]
     * @param   [type]                         $instance [description]
     * @return  [type]                                   [description]
     */
    public function widget($args, $instance)
    {
        global $wordpress_country_selector_options;

        extract($instance);

        // $currentCountry = $this->get_sites_country();

        $activeCountries = array_filter($wordpress_country_selector_options, array($this, 'filter_countries'), ARRAY_FILTER_USE_KEY);
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
            
            if(isset($users_url['path'])) {

                $path = explode('/', $users_url['path']);
                
                if(isset($path[1])) {
                    $users_url = $users_url["scheme"] . "://" . $users_url["host"] . '/' . $path[1] . '/';
                } else {
                    $users_url = $users_url["scheme"] . "://" . $users_url["host"];
                }
            } else {

            }
        }

        $currentCountry = str_replace('country', '', array_search($users_url, $activeCountries));

        if(!isset($activeCountries['country' . $currentCountry]) || empty($activeCountries['country' . $currentCountry])) {
            $currentCountry = 'UN';
        }

        $html = $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			$html .= $args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title'];
		}

        // Set Countries
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'data/countries.php')) {
            require plugin_dir_path(dirname(__FILE__)).'data/countries.php';
        }

        $text = sprintf($text, $countries[$currentCountry]);

        // Dropdown
        if($style == "dropdown") {



            $html .=    '<div class="country_selector_dropdown">
                                <a href="#" class="country_selector_dropbtn"><span class="flag-icon flag-icon-' . strtolower($currentCountry) . '"></span> 
                                    ' . $text . 
                                '</a>
                                <div class="country_selector_dropdown-content">';

                                foreach ($activeCountries as $countryCode => $countryURL) {
                                    $countryCode = substr($countryCode, 7, 9);

                                    if($wordpress_country_selector_options['tryCorrectPage']) {
                                        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
                                                    "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        $countryURL = $countryURL . '/' . ltrim( str_replace($users_url, '', $current_url), '/');
                                    }

                                    $html .= '<a href="' . $countryURL . '" data-country-code="' . $countryCode . '"><span class="flag-icon flag-icon-' . strtolower($countryCode) . '"></span> ' . $countries[$countryCode] . '</a>';
                                }

            $html .=            '</div>
                            </div>';
        // Simple Link
        } else {
            $html .= '<a id="country_selector_modal_page_show" href="' . $wordpress_country_selector_options['pageURL'] . '?redirect=false">
                        <span class="flag-icon flag-icon-' . strtolower($currentCountry) . '"></span> ' . $text . '</a>';
        }
		
		$html .= $args['after_widget'];

        echo $html;
    }

    private function filter_countries($key) 
    {
        return substr($key, 0, 7) === 'country' && strlen($key) === 9;
    }

    /**
     * Save widget options
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                         $new_instance [description]
     * @param   [type]                         $old_instance [description]
     * @return  [type]                                       [description]
     */
    public function update($new_instance, $old_instance)
    {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';

		return $instance;
    }

    /**
     * Output admin widget options form
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                         $instance [description]
     * @return  [type]                                   [description]
     */
    public function form($instance)
    {
        // Title
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Title', 'wordpress-country-selector' );
        $text = ! empty( $instance['text'] ) ? $instance['text'] : __( 'Choose your country', 'wordpress-country-selector' );
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'link';
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
        // Text
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Select Your Country:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>">
        </p>
        <?php 
        // Style
        $text = ! empty( $instance['text'] ) ? $instance['text'] : __( 'Select Your Country', 'wordpress-country-selector' );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Select a Style' ); ?></label> 
        <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
            <option value='link'<?php echo ($style=='link') ? 'selected' : '';?>>Link to Country Selector Page</option>
            <option value='dropdown'<?php echo ($style=='dropdown') ? 'selected' : '';?>>Dropdown with all Countries</option>
        </select>
        </p>
        <?php 
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
