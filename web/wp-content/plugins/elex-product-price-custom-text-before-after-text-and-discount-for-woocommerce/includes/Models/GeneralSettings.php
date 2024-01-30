<?php

namespace ELEX\PPCT\Models;

class GeneralSettings {


	protected $plugin_switch;
	protected $all_pages;
	protected $pages;
	protected $prefix;
	protected $suffix;
	protected $discount_type;
	protected $discount_val;
	protected $form_data;

	public static function load() {
		$self = new self();

		$default_val = self::get_default_values();


		$self->plugin_switch = get_option( 'elex_ppct_check_field' );
		$self->all_pages = get_option( 'elex_ppct_select_all_pages_value' );
		$self->pages = get_option( 'elex_ppct_pages' );
		$self->prefix = get_option( 'elex_ppct_prefix_field' );
		$self->suffix = get_option( ' elex_ppct_suffix_field' );
		$self->discount_type = get_option( 'elex_ppct_discount_type' );
		$self->discount_val = get_option( 'elex_ppct_discount_amount' );
		$self->form_data = get_option( 'ppct_general_settings', $default_val );
		 

		return $self ;
	}

	public static function get_default_values() {

		$args = array(
			'taxonomy' => 'product_cat',
			'fields ' => 'ids',
			'get' => 'all',
		);
		$all_categories = get_categories( $args );

		$all_categories_ids = array();

		foreach ( $all_categories as $category ) {
			$all_categories_ids[] = $category->term_id;
		}


		$data = array(
			'elex_ppct_select_all_pages' => false,
			'general'          => array(
				'elex_ppct_success_message'     => __( 'Settings saved successfully', 'elex-product-price-custom-text-and-discount' ),
				'limit_button_on_certain_products' => array(
					'enabled'                      => false,
					'select_all'                   => true,
					'include_products_by_category' => $all_categories_ids,
					'include_products_by_name'     => array(),
					'include_products_by_tag'      => array(),
				),
				'exclude_products'                 => array(
					'enabled'     => false,
					'select_all'  => false,
					'by_category' => array(),
					'by_name'     => array(),
					'by_tag'      => array(),
				),
				'role_based_filter'                => array(
					'enabled'       => false,
					'include_roles' => array(),
					'exclude_roles' => array(),

				),
			),
			 

		);
		return $data;
	}


	public function save() {

		update_option( 'elex_ppct_check_field', $this->form_data['elex_ppct_check_field'] );
		update_option( 'elex_ppct_select_all_pages_value', $this->form_data['elex_ppct_select_all_pages'] );
		update_option( 'elex_ppct_pages', $this->form_data['elex_custom_text_selected_pages'] );
		update_option( 'elex_ppct_prefix_field', $this->form_data['elex_ppct_prefix_field'] );
		update_option( 'elex_ppct_suffix_field', $this->form_data['elex_ppct_suffix_field'] );
		update_option( 'elex_ppct_discount_type', $this->form_data['elex_ppct_discount_type'] );
		update_option( 'elex_ppct_discount_amount', $this->form_data['elex_ppct_discount_amount'] );

		update_option( 'ppct_general_settings', $this->form_data );
		 
		return $this;
	}

	public static function get_products( $search_key ) {

		$product_data      = array();
		$product_data_temp = array();
		$args              = array(
			's' => $search_key,
		);
		$products          = wc_get_products( $args );
		foreach ( $products as $product ) {
			$product_data['id']   = $product->get_id();
			$product_data['text'] = $product->get_name();
			$product_data_temp[]  = $product_data;
		}
		return $product_data_temp;
	}

	public static function get_products_by_category( $search_key ) {
		$product_category_data      = array();
		$product_category_data_temp = array();
		$product_category           = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'search'   => $search_key,
			)
		);
		foreach ( $product_category as $category ) {
			$product_category_data['id']   = $category->term_id;
			$product_category_data['text'] = $category->name;
			$product_category_data_temp[]  = $product_category_data;
		}
		return $product_category_data_temp;
	}


	public static function get_user_role( $search_key ) {
		global $wp_roles;

		$user_roles                 = $wp_roles->role_names;
		$user_roles['unregistered'] = 'Unregistered';
		return $user_roles;
	}


	public static function get_products_by_tag( $search_key ) {
		$product_category_data      = array();
		$product_category_data_temp = array();
		$product_category           = get_terms(
			array(
				'taxonomy' => 'product_tag',
				'search'   => $search_key,

			)
		);
		foreach ( $product_category as $category ) {
			$product_category_data['id']   = $category->term_id;
			$product_category_data['text'] = $category->name;
			$product_category_data_temp[]  = $product_category_data;
		}
		return $product_category_data_temp;
	}
	 
	public function merge( $new_options ) {
		$this->form_data = array_merge( $this->form_data, $new_options );
		return $this;
	}


	public  function to_array() {
		return $this->form_data;
	}

	public function get( $key ) {
		return $this->form_data;
	}

}
