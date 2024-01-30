<?php
namespace ELEX\PPCT ;

use ELEX\PPCT\Models\GeneralSettings ;

class SettingsController {

	public static $settings = null;

	public static function init() {
		add_action( 'ppct_settings_tab_general', array( self::class, 'load_general' ) );
		add_action( 'ppct_settings_tab_customization', array( self::class, 'load_customization' ) );
		add_filter( 'ppct_settings_saving_general', array( self::class, 'save_general' ) );
		add_filter( 'ppct_settings_saving_customization', array( self::class, 'save_customization' ) );


		add_action( 'wp_ajax_search_products_by_name', array( self::class, 'search_products_by_name' ) );
		add_action( 'wp_ajax_search_products_by_category', array( self::class, 'search_products_by_category' ) );
		add_action( 'wp_ajax_search_products_by_tag', array( self::class, 'search_products_by_tag' ) );
		add_action( 'wp_ajax_search_user_role', array( self::class, 'search_user_role' ) );

		//for simple product price
		add_filter( 'woocommerce_product_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

		// for variable product each variation price.
		add_filter( 'woocommerce_product_variation_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

		add_filter( 'woocommerce_get_price_html', array( self::class, 'elex_ppct_display_price' ), 999, 2 );
		$plugin_enable_check = get_option( 'elex_ppct_check_field' );
		$selected_pages      = array();
		if ( null !== get_option( 'elex_ppct_pages' ) ) {
			$selected_pages = get_option( 'elex_ppct_pages', array() );
		}
		if ( in_array( 'cart_page', $selected_pages ) && ( ! empty( $plugin_enable_check ) && ( 'yes' === $plugin_enable_check || $plugin_enable_check ) ) ) {
			add_filter( 'woocommerce_cart_item_price', array( self::class, 'elex_ppct_display_price_cart' ), 10, 3 );
		}
		add_filter( 'woocommerce_variable_price_html', array( self::class, 'elex_variation_price_format' ), 999, 2 );

		add_action( 'plugins_loaded', array( self::class, 'elex_ppct_load_plugin_textdomain' ) );
		add_action( 'admin_init', array( self::class, 'elex_plugin_active' ) );

		if ( empty( get_option( 'elex_ppct_pages' ) ) ) {
			$default_pages = array(
				'shop_page',
				'product_page',
			);
			update_option( 'elex_ppct_pages', $default_pages );
		}

	}

	public static function elex_plugin_active() {
		$has_migration = get_option( 'elex_ppct_migration' );   
		if ( '2' === $has_migration || 2 === $has_migration ) {
			return;
		}
	   $amount  = get_option( 'elex_ppct_discount_amount' );
	   $percent = get_option( 'elex_ppct_discount_percent' );
		if ( intval( $amount ) > 0 ) {
			add_option( 'elex_ppct_discount_type', 'amount' );
		} else {
			add_option( 'elex_ppct_discount_type', 'percent' );
			update_option( 'elex_ppct_discount_amount', $percent );
		}
	   delete_option( 'elex_ppct_discount_percent' );
   
		if ( ( empty( get_option( 'elex_ppct_check_field' ) ) ) ) {
			$args = array(
				'taxonomy' => 'product_cat',
				'fields ' => 'ids',
				'get' => 'all',
			);
			$all_categories = get_categories( $args );
			foreach ( $all_categories as $all_category ) {
				$all_category_ids[] = $all_category->term_id;
			}
		}
   
		if ( empty( get_option( 'elex_ppct_pages' ) ) ) {
			$default_pages = array(
				'shop_page',
				'product_page',
			);
			update_option( 'elex_ppct_pages', $default_pages );
		}
   
	   global $wpdb;
   
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_custom_fields_prefix_checkbox','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_custom_fields_prefix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_custom_fields_suffix_checkbox','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_custom_fields_suffix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_custom_fields_discount_type_checkbox','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_discount_amount' AND meta_value != ''" );
   
	   // For use custom text for variations
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_custom_text_plugin','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_add_prefix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_custom_text_plugin','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_add_suffix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_custom_text_plugin','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_discount_amount' AND meta_value != ''" );
   
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_prefix_post_meta','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_add_prefix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_suffix_post_meta','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_add_suffix' AND meta_value != ''" );
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_variation_use_discount_post_meta','yes' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_discount_amount' AND meta_value != ''" );
   
	   $wpdb->query( "INSERT INTO  {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_discount_type','amount' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_custom_fields_discount_amount' AND meta_value>0" );
	   $wpdb->query( "UPDATE  {$wpdb->prefix}postmeta SET meta_key='elex_ppct_discount_amount' WHERE meta_key='elex_ppct_custom_fields_discount_amount' AND meta_value>0" );
   
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_discount_type','percent' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_custom_fields_discount_percent' AND meta_value>0" );
	   $wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_key='elex_ppct_discount_amount' WHERE meta_key='elex_ppct_custom_fields_discount_percent' AND meta_value>0" );
   
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_discount_type','amount' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_discount_amount' AND meta_value>0" );
	   $wpdb->query( "UPDATE  {$wpdb->prefix}postmeta SET meta_key='elex_ppct_discount_amount' WHERE meta_key='elex_ppct_variation_discount_amount' AND meta_value>0" );
   
	   $wpdb->query( "INSERT INTO {$wpdb->prefix}postmeta(post_id,meta_key,meta_value) SELECT post_id,'elex_ppct_discount_type','percent' FROM {$wpdb->prefix}postmeta WHERE meta_key='elex_ppct_variation_discount_percent' AND meta_value>0" );
	   $wpdb->query( "UPDATE  {$wpdb->prefix}postmeta SET meta_key='elex_ppct_discount_amount' WHERE meta_key='elex_ppct_variation_discount_percent' AND meta_value>0" );
   
	   $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key IN ('elex_ppct_custom_fields_discount_amount', 'elex_ppct_custom_fields_discount_percent','elex_ppct_variation_discount_amount','elex_ppct_variation_discount_percent')" );
   
	   update_option( 'elex_ppct_migration', 2 );
	}

	public static function elex_ppct_return_wpml_string( $string_to_translate, $name ) {
		// https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620585
		  // https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620618
		  $package = array(
			  'kind' => 'Elex Product Price Custom Text and Discount',
			  'name' => 'elex-product-price-custom-text-and-discount',
			  'title' => $name,
			  'edit_link' => '',
		  );
		  /**
		   * To register the string in wpml
		   *
		   * @since 1.1.6
		   */
		  do_action( 'wpml_register_string', $string_to_translate, $name, $package, $name, 'LINE' );
		  /**
		   * To translate string using wpml
		   *
		   * @since 1.1.6
		   */
		  $ret_string = apply_filters( 'wpml_translate_string', $string_to_translate, $name, $package );
		  return $ret_string;
	}
	  // for variable price range
	public static function elex_variation_price_format( $price, $product ) {
		// Get min/max regular and sale variation prices
		if ( $product->is_type( 'variable' ) ) {
			$prices = $product->get_variation_prices( true );
			if ( empty( $prices['price'] ) ) {
				return $price;
			}
			foreach ( $prices['price'] as $pid => $old_price ) {
				$pobj                    = wc_get_product( $pid );
				$prices['price'][ $pid ] = wc_get_price_to_display( $pobj );
			}
			asort( $prices['price'] );
			asort( $prices['regular_price'] );
			$min_price = current( $prices['price'] );
			$max_price = end( $prices['price'] );
	  
			if ( $min_price !== $max_price ) {
				$price = wc_format_price_range( $min_price, $max_price ) . $product->get_price_suffix();
			}
		}
		return $price;
	}
	  
	  
	  
	  
	  /**
	   * Load Plugin Text Domain. 
	   */
	public static function elex_ppct_load_plugin_textdomain() {
	   load_plugin_textdomain( 'elex-product-price-custom-text-and-discount', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	  


	// to get the base price of the product.
	public static function elex_ppct_base_price( $product ) {
	remove_filter( 'woocommerce_product_get_regular_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );
   remove_filter( 'woocommerce_product_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

   remove_filter( 'woocommerce_product_variation_get_regular_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );
   remove_filter( 'woocommerce_product_variation_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

   $base_price = $product->get_regular_price();
   // for simple product price.
   add_filter( 'woocommerce_product_get_regular_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );
   add_filter( 'woocommerce_product_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

   // for variable product each variation price.
   add_filter( 'woocommerce_product_variation_get_regular_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );
   add_filter( 'woocommerce_product_variation_get_price', array( self::class, 'elex_ppct_discount_product' ), 8, 2 );

   return $base_price;
	}

	public static function elex_ppct_get_price_to_display( $price, $product ) {
	  $global_check_field = get_option( 'elex_ppct_check_field' );

	   $tax_options = get_option( 'woocommerce_tax_display_shop' );
   


	   $base_price = self::elex_ppct_base_price( $product );
	   $price_excl_tax = wc_get_price_excluding_tax( $product ); // price without VAT
	   $price_incl_tax = wc_get_price_including_tax( $product );  // price with VAT
	   $product_price = $product->get_price();
	   $tax     = ( $price_incl_tax - $price_excl_tax );
		if ( ( isset( $tax ) ) && ! empty( $tax ) && 'incl' === $tax_options ) {
		 $tax_percent = ( $tax / $price_excl_tax ) * 100;
		 $base_tax = 1 + ( $tax_percent / 100 );
		 $base_price *= $base_tax;
		 $product_price *= $base_tax;
		}

		if ( $product->is_on_sale() && wc_get_price_to_display( $product ) !== $base_price ) {
			$price = wc_format_sale_price( $base_price, wc_get_price_to_display( $product ) );
		} elseif ( $product->get_regular_price() !== $base_price && $base_price !== $product_price ) {
			$price = wc_format_sale_price( $base_price, $product_price );
		}
   return $price;
	}

	public static function elex_ppct_display_price( $price, $product ) {
		$selected_pages = array();
		if ( null !== get_option( 'elex_ppct_pages' ) ) {
			$selected_pages = get_option( 'elex_ppct_pages', array() );
		}
		if ( ! in_array( 'shop_page', $selected_pages ) && is_shop() ) {
			return $price;
		}
		if ( ! in_array( 'product_page', $selected_pages ) && is_product() ) {
			return $price;
		}

		if ( $product->is_type( 'variation' ) ) {
			$product_id = $product->get_parent_id();
			$variation_id = $product->get_id();
		} else {
			$product_id = $product->get_id();
		}
		if ( ! $product->is_type( 'variable' ) ) {
			$price = self::elex_ppct_get_price_to_display( $price, $product );
		}
		$categories = ! empty( get_the_terms( $product_id, 'product_cat' ) ) ? get_the_terms( $product_id, 'product_cat' ) : array();
		$category_id = array();

		foreach ( $categories as $category ) {
			$category_id[] = $category->term_id;
		}

   $filterProducts = get_option( 'ppct_general_settings' );
   $include_products = ! empty( $filterProducts['general']['limit_button_on_certain_products'] ) ? $filterProducts['general']['limit_button_on_certain_products'] : array();
   $exclude_products = ! empty( $filterProducts['general']['exclude_products'] ) ? $filterProducts['general']['exclude_products'] : array();
   $role_based_filter = ! empty( $filterProducts['general']['role_based_filter'] ) ? $filterProducts['general']['role_based_filter'] : array();

   // Get the current user ID
   $user_id = get_current_user_id();
   $current_user_role = array();

   // Check if the user ID is not empty
		if ( $user_id ) {
		// Get the user object
		$user = get_userdata( $user_id );

		// Check if the user object is not empty
			if ( $user ) {
			// Get the user roles
			$user_roles = $user->roles;

			// Check if the user has roles
				if ( ! empty( $user_roles ) ) {
					// Get the first user role (assuming a user has only one role)
					$current_user_role = array( $user_roles[0] );
				}
			}
		}

   $tags = get_the_terms( $product_id, 'product_tag' ); // Assuming 'product_tag' is the taxonomy for product tags.
		if ( false === $tags ) {
			$tags = array();
		}

   $tag_ids = array();

		foreach ( $tags as $tag ) {
			$tag_ids[] = $tag->term_id;
		}

   $product_info = wc_get_product( $product_id );
   $custom_check_enable = $product_info->get_meta( 'elex_ppct_custom_fields_checkbox' );
   $check_enable = get_option( 'elex_ppct_check_field' );
   $var_product = ! empty( $variation_id ) ? wc_get_product( $variation_id ) : '';
		if ( ! empty( $variation_id ) ) {
			$use_custom_text_variation = $var_product ->get_meta( 'elex_ppct_variation_use_custom_text_plugin' );
			$global_prefix = get_option( 'elex_ppct_prefix_field' );
			$global_suffix = get_option( 'elex_ppct_suffix_field' );
			if ( ( 'yes' === $check_enable || $check_enable ) && ( 'yes' !== $custom_check_enable ) && ( 'yes' !== $use_custom_text_variation ) ) {
				$price = $global_prefix . ' ' . $price . ' ' . $global_suffix;
				return $price;
			}
		}

		if ( ( ! empty( $variation_id ) && ( 'yes' === $use_custom_text_variation ) ) ) {
			$use_prefix_variation    = $var_product ->get_meta( 'elex_ppct_variation_use_prefix_post_meta' );
			$use_suffix_variation    = $var_product ->get_meta( 'elex_ppct_variation_use_suffix_post_meta' );
			$add_prefix              = $var_product ->get_meta( 'elex_ppct_variation_add_prefix' );
			$custom_variation_prefix = self::get_price_before_text_html( $add_prefix );
			$add_suffix              = $var_product ->get_meta( 'elex_ppct_variation_add_suffix' );
			$custom_variation_suffix = self::get_price_after_text_html( $add_suffix ); 

			if ( 'yes' === $use_prefix_variation ) {
				$price = $custom_variation_prefix . ' ' . $price;
			}
			if ( 'yes' === $use_suffix_variation ) {
				$price = $price . ' ' . $custom_variation_suffix;
			}

			return $price;

		} elseif ( ( 'yes' === $custom_check_enable ) && ! empty( $price ) && ( empty( $use_custom_text_variation ) || 'no' === $use_custom_text_variation ) ) {
			$prefix_checkbox        = $product_info->get_meta( 'elex_ppct_custom_fields_prefix_checkbox' );
			$suffix_checkbox        = $product_info->get_meta( 'elex_ppct_custom_fields_suffix_checkbox' );

			$price_before_text      = $product_info->get_meta( 'elex_ppct_custom_fields_prefix' );
			$price_after_text       = $product_info->get_meta( 'elex_ppct_custom_fields_suffix' );
			$price_before_text_html = self::get_price_before_text_html( $price_before_text );
			$price_after_text_html  = self::get_price_after_text_html( $price_after_text );
			if ( ( 'yes' === $suffix_checkbox ) && ( empty( $prefix_checkbox ) || 'no' === $prefix_checkbox ) ) {
				$price = $price . ' ' . $price_after_text_html;
			} elseif ( ( empty( $suffix_checkbox ) || 'no' === $suffix_checkbox ) && ( 'yes' === $prefix_checkbox ) ) {
				$price = $price_before_text_html . ' ' . $price;
			} elseif ( ( 'yes' === $prefix_checkbox ) && ( 'yes' === $suffix_checkbox ) ) {
				$price = $price_before_text_html . ' ' . $price . ' ' . $price_after_text_html;
			}
			return $price;
		} elseif ( ( ( 'yes' === $check_enable || $check_enable ) && ( empty( $custom_check_enable ) || 'no' === $custom_check_enable ) ) && ! empty( $price ) ) {
			
			$price_before_text = get_option( 'elex_ppct_prefix_field' );
			$price_after_text  = get_option( 'elex_ppct_suffix_field' );
	
			$price_before_text_html = self::get_price_before_text_html( $price_before_text );
			$price_after_text_html  = self::get_price_after_text_html( $price_after_text );
			$price = $price_before_text_html . ' ' . $price . ' ' . $price_after_text_html;

			return $price;
			
		} else {
			return $price;
		}
	}

	public static function get_price_before_text_html( $price_before_text ) {
		$settings = get_option( 'elex_ppct_customization_data' );
		$font_family = ( empty( $settings['elex_ppct_font_family'] ) || 'default' === $settings['elex_ppct_font_family'] ) ? '' : 'font-family:' . $settings['elex_ppct_font_family'] . ';'  ;
		$font_size = ( empty( $settings['elex_ppct_font_size'] ) || 'default' === $settings['elex_ppct_font_size'] ) ? '' : 'font-size:' . $settings['elex_ppct_font_size'] . ';'  ;
		$font_color = ( ! empty( $settings['elex_ppct_font_color'] ) ) ? 'color:' . $settings['elex_ppct_font_color'] . ';' : '#707070' ;
	   $style = $font_family . $font_size . $font_color ;
		if ( ! empty( $price_before_text ) ) {
		 $price_before_text = self::elex_ppct_return_wpml_string( $price_before_text, 'Prefix text - Product' );
		 return '<span style="' . $style . ' " class="elex-ppct-before-text">' . $price_before_text . '</span>';
		} else {
		return $price_before_text;
		}
	}

	public static function get_price_after_text_html( $price_after_text ) {
	  $settings = get_option( 'elex_ppct_customization_data' );
	  $font_family = ( empty( $settings['elex_ppct_font_family'] ) || 'default' === $settings['elex_ppct_font_family'] ) ? '' : 'font-family:' . $settings['elex_ppct_font_family'] . ';'  ;
	  $font_size = ( empty( $settings['elex_ppct_font_size'] ) || 'default' === $settings['elex_ppct_font_size'] ) ? '' : 'font-size:' . $settings['elex_ppct_font_size'] . ';'  ;
	  $font_color = ( ! empty( $settings['elex_ppct_font_color'] ) ) ? 'color:' . $settings['elex_ppct_font_color'] . ';' : '#707070' ;
	  $style = $font_family . $font_size . $font_color ;
		if ( ! empty( $price_after_text ) ) {
		 $price_after_text = self::elex_ppct_return_wpml_string( $price_after_text, 'Suffix text - Product' );
		 return '<span style="' . $style . ' " class="elex-ppct-after-text">' . $price_after_text . '</span>';
		} else {
		return $price_after_text;
		}
	}

	public static function elex_ppct_display_price_cart( $price, $cart_item, $cart_item_key ) {
	  $product = $cart_item['data'];
	   $check_enable = get_option( 'elex_ppct_check_field' );
		if ( is_object( $product ) && method_exists( $product, 'is_type' ) ) {
			if ( $product->is_type( 'variation' ) ) {
				  $product_id = $product->get_parent_id();
				  $variation_id = $product->get_id();
			} else {
				$product_id = $product->get_id();
			}
			if ( ! $product->is_type( 'variable' ) ) {
				 $price = self::elex_ppct_get_price_to_display( $price, $product );
			}
		 $categories = get_the_terms( $product_id, 'product_cat' );
		 $category_id = array();
   
			foreach ( $categories as $category ) {
				$category_id[] = $category->term_id;
			}

		 $product_info = wc_get_product( $product_id );
   
		 $custom_check_enable = $product_info->get_meta( 'elex_ppct_custom_fields_checkbox' );
		 $var_product = ! empty( $variation_id ) ? wc_get_product( $variation_id ) : '';
			if ( ! empty( $variation_id ) ) {
				$use_custom_text_variation = $var_product->get_meta( 'elex_ppct_variation_use_custom_text_plugin' );
				$global_prefix = get_option( 'elex_ppct_prefix_field' );
				$global_suffix = get_option( 'elex_ppct_suffix_field' );
				if ( 'yes' === $check_enable && ( 'yes' !== $custom_check_enable ) && ( 'yes' !== $use_custom_text_variation ) ) {
					$price = $global_prefix . ' ' . $price . ' ' . $global_suffix;
					return $price;
				}
			}

			if ( ( ! empty( $variation_id ) && ( 'yes' === $use_custom_text_variation ) ) ) {
				$use_prefix_variation    = $var_product->get_meta( 'elex_ppct_variation_use_prefix_post_meta' );
				$use_suffix_variation    = $var_product->get_meta( 'elex_ppct_variation_use_suffix_post_meta' );
				$add_prefix              = $var_product->get_meta( 'elex_ppct_variation_add_prefix' );
				$custom_variation_prefix = self::get_price_before_text_html( $add_prefix );
				$add_suffix              = $var_product->get_meta( 'elex_ppct_variation_add_suffix' );
				$custom_variation_suffix = self::get_price_after_text_html( $add_suffix ); 
   
				if ( 'yes' === $use_prefix_variation ) {
					$price = $custom_variation_prefix . ' ' . $price;
				}
				if ( 'yes' === $use_suffix_variation ) {
					$price = $price . ' ' . $custom_variation_suffix;
				}
   
				return $price;
   
			} elseif ( ( 'yes' === $custom_check_enable ) && ! empty( $price ) && ( empty( $use_custom_text_variation ) || 'no' === $use_custom_text_variation ) ) {
				$prefix_checkbox        = $product_info->get_meta( 'elex_ppct_custom_fields_prefix_checkbox' );
				$suffix_checkbox        = $product_info->get_meta( 'elex_ppct_custom_fields_suffix_checkbox' );
				$price_before_text      = $product_info->get_meta( 'elex_ppct_custom_fields_prefix' );
				$price_after_text       = $product_info->get_meta( 'elex_ppct_custom_fields_suffix' );
				$price_before_text_html = self::get_price_before_text_html( $price_before_text );
				$price_after_text_html  = self::get_price_after_text_html( $price_after_text );
				if ( ( 'yes' === $suffix_checkbox ) && ( empty( $prefix_checkbox ) || 'no' === $prefix_checkbox ) ) {
					$price = $price . ' ' . $price_after_text_html;
				} elseif ( ( empty( $suffix_checkbox ) || 'no' === $suffix_checkbox ) && ( 'yes' === $prefix_checkbox ) ) {
					$price = $price_before_text_html . ' ' . $price;
				} elseif ( ( 'yes' === $prefix_checkbox ) && ( 'yes' === $suffix_checkbox ) ) {
					$price = $price_before_text_html . ' ' . $price . ' ' . $price_after_text_html;
				}
				return $price;
			} elseif ( ( ( 'yes' === $check_enable || $check_enable ) && ( empty( $custom_check_enable ) || 'no' === $custom_check_enable ) ) && ! empty( $price ) ) {
				$price_before_text = get_option( 'elex_ppct_prefix_field' );
				$price_after_text  = get_option( 'elex_ppct_suffix_field' );
				$price_before_text_html = self::get_price_before_text_html( $price_before_text );
				$price_after_text_html  = self::get_price_after_text_html( $price_after_text );
				$price = $price_before_text_html . ' ' . $price . ' ' . $price_after_text_html;

				return $price;
			}
		} else {
			return $price;
		}

	}

	// elex_ppct_discount
	public static function elex_ppct_discount( $price, $discount, $type ) {   
		if ( 'amount' === $type && is_numeric( $price ) ) {
			$price = ( $price - ( ( float ) $discount ) );
		}
		if ( 'percent' === $type && is_numeric( $price ) ) {
			$price = ( $price - ( $price * ( ( float ) $discount / 100 ) ) );
		}
	return $price;
	}

//fetch data from db
	public static function elex_ppct_discount_product( $price, $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$product_id   = $product->get_parent_id();
			$variation_id = $product->get_id();
		} else {
			$product_id = $product->get_id();
		}
		$product_info = wc_get_product( $product_id );
		$custom_check_enable      = $product_info->get_meta( 'elex_ppct_custom_fields_checkbox' );
		$check_enable             = get_option( 'elex_ppct_check_field' );
		$custom_discount_checkbox = $product_info->get_meta( 'elex_ppct_custom_fields_discount_type_checkbox' );

		if ( ! empty( $variation_id ) ) {
			$var_product = wc_get_product( $variation_id );
			$variation_discount_checkbox = $var_product->get_meta( 'elex_ppct_variation_use_discount_post_meta' );
			$use_custom_text_variation = $var_product->get_meta( 'elex_ppct_variation_use_custom_text_plugin' );
			if ( ( 'yes' === $use_custom_text_variation ) && ( 'yes' === $variation_discount_checkbox ) ) {
				$_discount = $var_product->get_meta( 'elex_ppct_discount_amount' );  
				$_discount = ( float ) $_discount;
				$_option   = $var_product->get_meta( 'elex_ppct_discount_type' );
	
				$price = self::elex_ppct_discount( $price, $_discount, $_option );
	
				return $price;
			} 
		}

		if ( ( 'yes' === $custom_check_enable ) && ( 'yes' === $custom_discount_checkbox ) ) {
			$_discount = $product_info->get_meta( 'elex_ppct_discount_amount' );  
			$_discount = ( float ) $_discount;
			$_option   = $product_info->get_meta( 'elex_ppct_discount_type' );

			$price = self::elex_ppct_discount( $price, $_discount, $_option );

			return $price;
		} elseif ( ( 'yes' === $check_enable || $check_enable ) && ( 'no' === $custom_check_enable || '' === $custom_check_enable ) ) {
			$discount_ = get_option( 'elex_ppct_discount_amount' );
			$discount_ = ( float ) $discount_;
			$option    = get_option( 'elex_ppct_discount_type' );

			$price = self::elex_ppct_discount( $price, $discount_, $option );
			return $price;
		} else {
			return $price;
		}
	
	}

	public static function search_products_by_name() {
		// Get Product Name.
		check_ajax_referer( 'ppct-ajax-nonce', 'ppct_nonce' );

		$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$products_array = GeneralSettings::get_products( $search_key );
		wp_send_json_success( $products_array );


	}

	public static function search_products_by_category() {
		// Get Product Name.
		check_ajax_referer( 'ppct-ajax-nonce', 'ppct_nonce' );
		$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$products_array = GeneralSettings::get_products_by_category( $search_key );
		wp_send_json_success( $products_array );


	}

	public static function search_products_by_tag() {
		// Get Product Name.
		check_ajax_referer( 'ppct-ajax-nonce', 'ppct_nonce' );
		$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$products_array = GeneralSettings::get_products_by_tag( $search_key );
		wp_send_json_success( $products_array );


	}

	public static function search_user_role() {
		// Get User role Name.
		check_ajax_referer( 'ppct-ajax-nonce', 'ppct_nonce' );

			
		$search_key = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$roles      = GeneralSettings::get_user_role( $search_key );
		wp_send_json_success( $roles );

	}

	public static function load_general() {
		$settings = self::get_settings();
		
		$data = self::load_settings_data();

		$filter_settings = get_option( 'ppct_general_settings' );
		$include_products_select_all = isset( $filter_settings['general']['limit_button_on_certain_products']['select_all'] ) ? $filter_settings['general']['limit_button_on_certain_products']['select_all'] : array();
		$exclude_products_select_all = isset( $filter_settings['general']['exclude_products']['select_all'] ) ? $filter_settings['general']['exclude_products']['select_all'] : array();

		$include_products_by_name = isset( $data['include_products_by_name'] ) ? $data['include_products_by_name'] : array();
		$exclude_products_by_name = isset( $data['exclude_products_by_name'] ) ? $data['exclude_products_by_name'] : array();

		$products_by_cat              = self::load_categories();
		$include_products_by_category = isset( $products_by_cat['include_products_by_cat'] ) ? $products_by_cat['include_products_by_cat'] : array();
		$exclude_products_by_category = isset( $products_by_cat['exclude_products_by_cat'] ) ? $products_by_cat['exclude_products_by_cat'] : array();

		$roles = self::get_user_roles();

		$include_roles = isset( $roles['include'] ) && ! empty( $roles['include'] ) ? $roles['include'] : array();
		$exclude_roles = isset( $roles['exclude'] ) && ! empty( $roles['exclude'] ) ? $roles['exclude'] : array();

		$products_by_tag         = self::load_products_by_tags();
		$include_products_by_tag = isset( $products_by_tag['include_products_by_tag'] ) ? $products_by_tag['include_products_by_tag'] : array();
		$exclude_products_by_tag = isset( $products_by_tag['exclude_products_by_tag'] ) ? $products_by_tag['exclude_products_by_tag'] : array();

		include ELEX_PPCT_MAIN_VIEWS . 'settings/general.php';
		self::show_saved_toast();

	}

	public static function load_customization() {

		$settings = self::get_settings();

		include ELEX_PPCT_MAIN_VIEWS . 'customization.php';
		self::show_saved_toast();

	}


	public static function show_saved_toast() {
		if ( isset( $_SESSION['saved_settings_data'] ) ) {
			include ELEX_PPCT_MAIN_VIEWS . 'saved_toast.php';
		}

	}

	public static function load_products_by_tags() {
		$settings     = self::get_settings();
		$data         = array();
		$category_ids = array_merge(
			$settings['general']['limit_button_on_certain_products']['include_products_by_tag'],
			$settings['general']['exclude_products']['by_tag']
		);
		if ( empty( $category_ids ) ) {
			return $data;
		}

		$terms = get_terms(
			array(
				'taxonomy' => 'product_tag',
				'include'  => $category_ids,
			)
		);

		$include_tags           = array();
		$exclude_tags           = array();


		foreach ( $terms as $term ) {
			if ( in_array( $term->term_id, $settings['general']['limit_button_on_certain_products']['include_products_by_tag'] ) ) {
				$include_tags[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}

			if ( in_array( $term->term_id, $settings['general']['exclude_products']['by_tag'] ) ) {
				$exclude_tags[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}       
		}
		
		$data['include_products_by_tag']           = $include_tags;
		$data['exclude_products_by_tag']           = $exclude_tags;


		return $data;

	}

	
	public static function get_user_roles() {
		global $wp_roles;
		$settings              = self::get_settings();
		$roles                 = $wp_roles->role_names;
		$roles['unregistered'] = 'Unregistered';
		$include_roles         = array();
		$exclude_roles         = array();
		$userroles             = array();
		foreach ( $roles as $key => $name ) {
			if ( in_array( $key, $settings['general']['role_based_filter']['include_roles'] ) ) {

				$include_roles[ $key ] = $name;
			}

			if ( in_array( $key, $settings['general']['role_based_filter']['exclude_roles'] ) ) {
				$exclude_roles[ $key ] = $name;

			}       
		}
		$userroles['include'] = $include_roles;
		$userroles['exclude'] = $exclude_roles;

		return $userroles;

	}

	public static function load_categories() {
		$settings = self::get_settings();
		$data = array();
		$category_ids = array_merge(
			$settings['general']['limit_button_on_certain_products']['include_products_by_category'],
			$settings['general']['exclude_products']['by_category']
		);
	
		if ( empty( $category_ids ) ) {
			return $data;
		}
	
		$include_categories = array();
		$exclude_categories = array();
	
		foreach ( $category_ids as $category_id ) {
			$terms = get_terms(
				array(
					'taxonomy' => 'product_cat',
					'include' => $category_id,
					'hide_empty' => false, // Include categories with no products
				)
			);
	
			foreach ( $terms as $term ) {
				if ( in_array( $term->term_id, $settings['general']['limit_button_on_certain_products']['include_products_by_category'] ) ) {
					$include_categories[] = array(
						'id' => $term->term_id,
						'name' => $term->name,
					);
				}
	
				if ( in_array( $term->term_id, $settings['general']['exclude_products']['by_category'] ) ) {
					$exclude_categories[] = array(
						'id' => $term->term_id,
						'name' => $term->name,
					);
				}
	
				// Fetch child terms
				$child_terms = get_terms(
					array(
						'taxonomy' => 'product_cat',
						'child_of' => $term->term_id,
						'hide_empty' => false,
					)
				);
	
				foreach ( $child_terms as $child_term ) {
					if ( in_array( $child_term->term_id, $settings['general']['limit_button_on_certain_products']['include_products_by_category'] ) ) {
						$include_categories[] = array(
							'id' => $child_term->term_id,
							'name' => $child_term->name,
						);
					}
	
					if ( in_array( $child_term->term_id, $settings['general']['exclude_products']['by_category'] ) ) {
						$exclude_categories[] = array(
							'id' => $child_term->term_id,
							'name' => $child_term->name,
						);
					}
				}
			}
		}
	
		$data['include_products_by_cat'] = $include_categories;
		$data['exclude_products_by_cat'] = $exclude_categories;
	
		return $data;
	}
	

	public static function load_settings_data() {
		$settings = self::get_settings();

		$mergerd_array = array_merge(
			$settings['general']['limit_button_on_certain_products']['include_products_by_name'],
			$settings['general']['exclude_products']['by_name']
		);

		$args  = array(
			'post_type'       => 'product',
			'include'         => $mergerd_array,
			'supress_filters' => false,
		);
		$terms = get_posts( $args );

		$include_names           = array();
		$exclude_names           = array();


		foreach ( $terms as $term ) {
			if ( in_array( $term->ID, $settings['general']['limit_button_on_certain_products']['include_products_by_name'] ) ) {
				$include_names[] = array(
					'id'   => $term->ID,
					'name' => $term->post_title,
				);
			}

			if ( in_array( $term->ID, $settings['general']['exclude_products']['by_name'] ) ) {
				$exclude_names[] = array(
					'id'   => $term->ID,
					'name' => $term->post_title,
				);
			}
		}

		$data['include_products_by_name']           = $include_names;
		$data['exclude_products_by_name']           = $exclude_names;


		return $data;

	}
	public static function get_settings( $reload = false ) {

		$settings       = GeneralSettings::load();
		$settings       = $settings->to_array();
		/**
		 * This is a filter hook which is responsible for settings
		 *
		 * @since 1.0.0
		 * 
		 * @param $settings
		 */
		self::$settings = apply_filters( 'ppct_settings', $settings );

		return  self::$settings;

	}

	public static function load_view() {
		global $plugin_page;
		$sub_tabs    = self::get_menus();
		$active_tab  = self::get_active_tab();
		$active_page = $plugin_page;

		if ( isset( $_POST['submit'] ) ) {

			check_admin_referer( 'save_settings', 'ppct_settings_nonce' );
			$settings = GeneralSettings::load();
			/**
			 * This is a filter hook which is responsible for loading the active tab
			 *
			 * @since 1.0.0
			 * 
			 * @param $settings
			 */
			$settings = apply_filters( 'ppct_settings_saving_' . self::get_active_tab(), $settings );
			$settings->save();
		}

		include ELEX_PPCT_MAIN_VIEWS . 'settings.php';
	}

	public static function get_menus() {

		$setting_menus = array(
			array(
				'title' => __( 'General Settings' ),
				'slug'  => 'general',
			),
			
			array(
				'title' => 'Customization',
				'slug'  => 'customization',
		
			),
		
		);

		/**
		 * This is a action hook which is responsible for taggling the tabs in help and support submenu
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'settings_tabs', $setting_menus );

		
	}

	public static function get_active_tab() {

		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		return ! empty( $tab ) ? $tab : self::get_default_tab();

	}

	public static function get_default_tab() {
		/**
		 * This is a action hook which is responsible for toggling the tabs in settings submenu
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'ppct_settings_default_tab', 'general' );
	}

	public static function save_general( GeneralSettings $general_setting_options ) {

		
		check_admin_referer( 'save_settings', 'ppct_settings_nonce' );
		$new_settings = array();
		$new_settings = $_POST;
		$new_settings['elex_ppct_check_field']                                                       = isset( $_POST['elex_ppct_check_field'] ) ? true : false;

		$new_settings['general']['limit_button_on_certain_products']['enabled']                      = isset( $_POST['general']['limit_button_on_certain_products']['enabled'] ) ? true : false;
		$new_settings['general']['limit_button_on_certain_products']['select_all']                   = isset( $_POST['general']['limit_button_on_certain_products']['select_all'] ) ? true : false;
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_category'] = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_category'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_category'], 'sanitize_text_field' ) : array();
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_name']     = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_name'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_name'], 'sanitize_text_field' ) : array();
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_tag']      = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_tag'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_tag'], 'sanitize_text_field' ) : array();
	   
		$new_settings['general']['exclude_products']['enabled']                                      = isset( $_POST['general']['exclude_products']['enabled'] ) ? true : false;
		$new_settings['general']['exclude_products']['select_all']                                   = isset( $_POST['general']['exclude_products']['select_all'] ) ? true : false;
		$new_settings['general']['exclude_products']['by_category']                                  = isset( $_POST['general']['exclude_products']['by_category'] ) ? map_deep( $_POST['general']['exclude_products']['by_category'], 'sanitize_text_field' ) : array();
		$new_settings['general']['exclude_products']['by_name']                                      = isset( $_POST['general']['exclude_products']['by_name'] ) ? map_deep( $_POST['general']['exclude_products']['by_name'], 'sanitize_text_field' ) : array();
		$new_settings['general']['exclude_products']['by_tag']                                       = isset( $_POST['general']['exclude_products']['by_tag'] ) ? map_deep( $_POST['general']['exclude_products']['by_tag'], 'sanitize_text_field' ) : array();
	   
		$new_settings['general']['role_based_filter']['enabled']                                     = isset( $_POST['general']['role_based_filter']['enabled'] ) ? true : false;
		$new_settings['general']['role_based_filter']['include_roles']                               = isset( $_POST['general']['role_based_filter']['include_roles'] ) ? map_deep( $_POST['general']['role_based_filter']['include_roles'], 'sanitize_text_field' ) : array();
		$new_settings['general']['role_based_filter']['exclude_roles']                               = isset( $_POST['general']['role_based_filter']['exclude_roles'] ) ? map_deep( $_POST['general']['role_based_filter']['exclude_roles'], 'sanitize_text_field' ) : array();
		
		return $general_setting_options->merge( $new_settings );
	}

	public static function save_customization( GeneralSettings $general_setting_options ) {

		check_admin_referer( 'save_settings', 'ppct_settings_nonce' );
		$custData = array();
		$custData = $_POST;

		return $general_setting_options->customData( $custData );

	}
}
