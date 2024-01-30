<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' ;
class Elex_Product_Price_Discount_Setting extends WC_Settings_Page {
	public function __construct() {
		ob_start();
		$this->init();
		$this->id = 'elex_ppct_discount';
	}

	public function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'elex_ppct_add_settings_tab' ), 50 );
		add_filter( 'woocommerce_sections_elex_ppct_discount', array( $this, 'output_sections' ) );
	}

	public function elex_ppct_add_settings_tab( $settings_tabs ) {
		$settings_tabs['elex_ppct_discount'] = __( 'Custom Text & Discount', 'elex-product-price-custom-text-and-discount' );
		return $settings_tabs;
	}
	public function get_sections() {
		$sections = array(
			''              => __( 'Custom Text & Discount', 'elex-product-price-custom-text-and-discount' ),
			'to-go-premium' => __( 'Related Products!', 'elex-product-price-custom-text-and-discount' ),
		);
		/**
		* To add setting fields
		*
		* @since 1.0.0
		*/
		return apply_filters( 'woocommerce_get_sections_elex_ppct_discount', $sections );
	}

	public function output_sections() {
		global $current_section;
		$sections = $this->get_sections();
		if ( empty( $sections ) || 1 === count( $sections ) ) {
			return;
		}
		if ( ob_get_level() > 0 ) {
			ob_end_flush();
		}
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			
			echo '<li><a href="' . esc_html( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( esc_html( $current_section ) === $id ? 'current elex_ppct_' . esc_html( $id ) : 'elex_ppct_' . esc_html( $id ) ) . '">' . esc_html( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
		}
		echo '</ul><br class="clear" />';
	}
}

add_action( 'woocommerce_settings_tabs_elex_ppct_discount', 'elex_ppct_discount_price_settings_tab' );
add_action( 'woocommerce_admin_field_elex_ppct_page_type', 'elex_ppct_page_type_field' );
add_action( 'woocommerce_admin_field_product_categories_type', 'product_category_field' );
function elex_ppct_discount_price_settings_tab() {
	woocommerce_admin_fields( elex_ppct_get_setting() );
}
function elex_ppct_page_type_field( $value ) {
	$selected_pages = array();
	if ( null !== get_option( 'elex_ppct_pages' ) ) {
		$selected_pages = get_option( 'elex_ppct_pages', array() );
	}
	?>

	<tr valign="top" class="elex_ppct_page_class">
		<th scope="row" class="titledesc">
			<label for="<?php esc_attr_e( $value['id'] ); ?>"><?php esc_attr_e( $value['title'] ); ?></label>
			<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( $value['desc'] ); ?>"></span>
		</th>
		<td class="forminp elex_ppct_page_td" id="elex_ppct_page_td_id">
			<div>
				<div>
					<input type="checkbox" name="elex_ppct_select_all_pages" id="elex_ppct_select_all_pages_id" <?php esc_attr_e( empty( get_option( 'elex_ppct_select_all_pages_value' ) ) || ( 'no' === get_option( 'elex_ppct_select_all_pages_value' ) ) ? '' : 'checked' ); ?>/>
					<strong><?php esc_attr_e( 'Select All Pages', 'elex-product-price-custom-text-and-discount' ); ?></strong>
				</div>
				<ul class="elex_pages" id="elex_ppct_pages">
					<li>
						<input class="elex_ppct_page_type" name="elex_custom_text_selected_pages[]" type="checkbox" value="shop_page"
							<?php esc_attr_e( ( empty( $selected_pages ) ) ? '' : ( ( in_array( 'shop_page', $selected_pages ) ) ? 'checked' : '' ) ); ?>/>
						<?php esc_attr_e( 'Shop Page', 'elex-product-price-custom-text-and-discount' ); ?>
					</li>
					<li>
						<input class="elex_ppct_page_type" name="elex_custom_text_selected_pages[]" type="checkbox" value="product_page"
							<?php esc_attr_e( ( empty( $selected_pages ) ) ? '' : ( ( in_array( 'product_page', $selected_pages ) ) ? 'checked' : '' ) ); ?>/>
						<?php esc_attr_e( 'Product Page', 'elex-product-price-custom-text-and-discount' ); ?>
					</li>
					<li><input class="elex_ppct_page_type" name="elex_custom_text_selected_pages[]" type="checkbox" value="cart_page"
							<?php esc_attr_e( ( empty( $selected_pages ) ) ? '' : ( ( in_array( 'cart_page', $selected_pages ) ) ? 'checked' : '' ) ); ?>>
						<?php esc_attr_e( 'Cart Page', 'elex-product-price-custom-text-and-discount' ); ?>
					</li>
				</ul>	
			</div>
		 </td>
	</tr>
	<?php
}

function product_category_field( $value ) {
	$parent_child_categories = array();
	$args = array(
		'taxonomy' => 'product_cat',
		'get' => 'all',
	);
	$all_categories = get_categories( $args );
	foreach ( $all_categories as $all_category ) {
		$category_id_array = array(
			'category_id'   => $all_category->term_id,
			'category_name' => $all_category->cat_name,
		);
		$parent_child_categories[ $all_category->category_parent ][] = $category_id_array;
	}
	?>

	<tr valign="top" class="product_categories_class">
		<th scope="row" class="titledesc">
			<label for="<?php esc_attr_e( $value['id'] . '_0' ); ?>"><?php esc_attr_e( $value['title'] ); ?></label>
			<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( $value['desc'] ); ?>"></span>
		</th>
		<td class="forminp elex_product_categories_td" id="elex_product_categories_td_id">
			<div>
				<div>
					<input type="checkbox" name="elex_ppct_select_all_categories" id="elex_ppct_select_all_categories_id" <?php esc_attr_e( empty( get_option( 'elex_ppct_select_all_categories_id' ) ) || ( 'no' === get_option( 'elex_ppct_select_all_categories_id' ) ) ? '' : 'checked' ); ?>/>
					<strong><?php esc_attr_e( 'Select All Categories', 'elex-product-price-custom-text-and-discount' ); ?></strong>
				</div>
			<?php render_cateogry( $parent_child_categories ); ?>
			</div>
		 </td>
	</tr>

	<?php
}
function render_cateogry( $parent_child_categories, $parent_id = 0 ) {
	$selected_categories = get_option( 'elex_ppct_categories' );
	$child_categories = $parent_child_categories[ $parent_id ];
	?>

	<ul class="elex_categories" id="elex_ppct_categories_<?php esc_attr_e( $parent_id ); ?>">
	<?php
	foreach ( $child_categories as $child_category ) {
		?>
		<li>
			<input class="elex_custom_text_categories" 
				id="elex_category_<?php esc_attr_e( $child_category['category_id'] ); ?>" 
				type="checkbox" value="<?php esc_attr_e( $child_category['category_id'] ); ?>"
				name="elex_custom_text_categories[]" <?php esc_attr_e( ( empty( $selected_categories ) ) ? '' : ( ( in_array( $child_category['category_id'], $selected_categories ) ) ? 'checked' : '' ) ); ?>/> 
				<?php 
				esc_attr_e( $child_category['category_name'] );
				if ( isset( $parent_child_categories[ $child_category['category_id'] ] ) ) {
					render_cateogry( $parent_child_categories, $child_category['category_id'] );
				}
				?>
		</li>
		<?php
	}
	?>
	</ul>
	<?php
}

function elex_ppct_get_setting() {
	global $current_section;
	$settings = array();
	if ( 'to-go-premium' === $current_section ) {
		include_once 'market.php';
	} else {
		?>
		<script type="text/javascript">
			jQuery(function(){
				const elex_ppct_select_all_categories_id = jQuery('#elex_ppct_select_all_categories_id');
				elex_ppct_select_all_categories_id.on('change',function() {
					if ( this.checked ) {
						jQuery('.elex_custom_text_categories').prop('checked', true);
					} else {
						jQuery('.elex_custom_text_categories').prop('checked', false);
					}
				});

				jQuery( '.elex_custom_text_categories' ).on( 'change', function() {
					// Check if any checkbox is not checked
					if ( jQuery( '.elex_custom_text_categories' ).filter( ':not(:checked)' ).length > 0 ) {
						jQuery( '#elex_ppct_select_all_categories_id' ).prop( 'checked', false );
					} else {
						jQuery( '#elex_ppct_select_all_categories_id' ).prop( 'checked', true );
					}
				});

				var checkboxes = jQuery( '.elex_custom_text_categories' );
				// Check if any checkbox is not checked
				if ( checkboxes.filter( ':not(:checked)' ).length > 0 ) {
					jQuery( '#elex_ppct_select_all_categories_id' ).prop( 'checked', false );
				} else {
					jQuery( '#elex_ppct_select_all_categories_id' ).prop( 'checked', true );
				}

				// Select page(s) js code
				const elex_ppct_select_all_pages_id = jQuery('#elex_ppct_select_all_pages_id');
				elex_ppct_select_all_pages_id.on('change',function() {
					if ( this.checked ) {
						jQuery('.elex_ppct_page_type').prop('checked', true);
					} else {
						jQuery('.elex_ppct_page_type').prop('checked', false);
					}
				});

				jQuery( '.elex_ppct_page_type' ).on( 'change', function() {
					// Check if any checkbox is not checked
					if ( jQuery( '.elex_ppct_page_type' ).filter( ':not(:checked)' ).length > 0 ) {
						jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', false );
					} else {
						jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', true );
					}
				});

				var page_checkboxes = jQuery( '.elex_ppct_page_type' );
				// Check if any checkbox is not checked
				if ( page_checkboxes.filter( ':not(:checked)' ).length > 0 ) {
					jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', false );
				} else {
					jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', true );
				}
				// End

				jQuery('.elex_custom_text_categories').on('change', function() {
					const category_id = jQuery(this).val();
					jQuery(this).next('ul').find('input').each(function() {
						const checked = jQuery('#elex_category_' + category_id ).is(':checked');
						jQuery(this).prop('checked', checked);
					});
				});

				if (jQuery('#elex_ppct_check_field').is(":checked")) {
					jQuery('#elex_ppct_prefix_field').closest("tr").show();
					jQuery('#elex_ppct_suffix_field').closest("tr").show();
					jQuery('#elex_ppct_discount_type').closest("tr").show();
					jQuery('#elex_ppct_discount_amount').closest("tr").show();
					jQuery('.elex_ppct_page_class').closest("tr").show();
					jQuery('#elex_ppct_categories').closest('tr').show();
					jQuery('.product_categories_class').show();
				} else {
					jQuery('#elex_ppct_prefix_field').closest("tr").hide();
					jQuery('#elex_ppct_discount_type').closest("tr").hide();
					jQuery('#elex_ppct_suffix_field').closest("tr").hide();
					jQuery('#elex_ppct_discount_amount').closest("tr").hide();
					jQuery('.elex_ppct_page_class').closest("tr").hide();
					jQuery('#elex_ppct_categories').closest('tr').hide();
					jQuery('.product_categories_class').hide();
				}
				
				jQuery("#elex_ppct_check_field").click(function(event){
					var value=jQuery(this).is(':checked');
					if(value === true ){
						jQuery('#elex_ppct_prefix_field').closest("tr").show();
						jQuery('#elex_ppct_discount_type').closest("tr").show();
						jQuery('#elex_ppct_suffix_field').closest("tr").show();
						jQuery('#elex_ppct_discount_amount').closest("tr").show();
						jQuery('.elex_ppct_page_class').closest("tr").show();
						jQuery('#elex_ppct_categories').closest('tr').show();
						jQuery('.product_categories_class').show();
					} else {
						jQuery('#elex_ppct_prefix_field').closest("tr").hide();
						jQuery('#elex_ppct_discount_type').closest("tr").hide();
						jQuery('#elex_ppct_suffix_field').closest("tr").hide();
						jQuery('#elex_ppct_discount_amount').closest("tr").hide();
						jQuery('.elex_ppct_page_class').closest("tr").hide();
						jQuery('.product_categories_class').hide();
						jQuery('#elex_ppct_categories').closest('tr').hide();
					}
				});
			});
		</script>
		<?php
		wp_nonce_field( basename( __FILE__ ), 'ppct-global-settings-fields-nonce' );

		$settings = array(
			'title' => array(
				'title' => __( 'Product Price Custom Text & Discount:', 'elex-product-price-custom-text-and-discount' ),
				'type'  => 'title',
				'id'    => 'elex_ppct_title_field',
			),
			'checkbox_enable' => array(
				'title' => __( 'Use Custom Text & Discount', 'elex-product-price-custom-text-and-discount' ),
				'type'  => 'checkbox',
				'desc'  => __( 'Enable', 'elex-product-price-custom-text-and-discount' ),
				'css'   => 'width:100%',
				'id'    => 'elex_ppct_check_field',
			),
			'elex_page' => array(
				'type'     => 'elex_ppct_page_type',
				'desc'     => __( 'The Custom Text & Discount will be applied to the selected page(s).', 'elex-product-price-custom-text-and-discount' ),
				'name'     => 'elex_ppct_page_name',
				'id'       => 'elex_ppct_page',
				'title'    => __( 'Select Pages', 'elex-product-price-custom-text-and-discount' ),
				'desc_tip' => true,
			),
			'categories' => array(
				'type'     => 'product_categories_type',
				'desc'     => __( 'Settings will be applied to the selected categories.', 'elex-product-price-custom-text-and-discount' ),
				'name'     => 'product_categories_name',
				'id'       => 'elex_ppct_categories',
				'title'    => __( 'Product Categories', 'elex-product-price-custom-text-and-discount' ),
				'desc_tip' => true,
			),
			'prefix_ppct'          => array(
				'title'    => __( 'Add Prefix', 'elex-product-price-custom-text-and-discount' ),
				'type'     => 'textarea',
				'desc'     => __( 'Add a prefix text with your product price', 'elex-product-price-custom-text-and-discount' ),
				'css'      => 'width:250px',
				'id'       => 'elex_ppct_prefix_field',
				'desc_tip' => true,
			),
			'suffix_ppct'          => array(
				'title'    => __( 'Add Suffix', 'elex-product-price-custom-text-and-discount' ),
				'type'     => 'textarea',
				'desc'     => __( 'Add a suffix text with your product price.', 'elex-product-price-custom-text-and-discount' ),
				'css'      => 'width:250px',
				'id'       => 'elex_ppct_suffix_field',
				'desc_tip' => true,
			),
			'Discount_type' => array(
				'title' => __( 'Discount Type', 'elex-product-price-custom-text-and-discount' ),
				'type' => 'select',
				'desc' => __( 'Specify the type you want to apply. This discount will be applied to all products.', 'elex-product-price-custom-text-and-discount' ),
				'class' => 'chosen_select',
				'id'      => 'elex_ppct_discount_type',
				'options' => array(
					'amount'  => __( 'Amount', 'elex-product-price-custom-text-and-discount' ),
					'percent' => __( 'Percent', 'elex-product-price-custom-text-and-discount' ),
				),
				'desc_tip' => true,
			),
			'discount_ppct'         => array(
				'title'             => __( 'Discount', 'elex-product-price-custom-text-and-discount' ),
				'type'              => 'number',
				'desc'              => __( 'Specify the discount you want to apply. This discount will be applied to all products.', 'elex-product-price-custom-text-and-discount' ),
				'css'               => 'width:250px',
				'custom_attributes' => array(
					'step' => 'any',
					'min' => 0,
				),
				'id'                => 'elex_ppct_discount_amount',
				'desc_tip'          => true,
			),
			'fp_title_end'         => array(
				'type' => 'sectionend',
				'id'   => 'ppct_end',
			),
		);
	}

	/**
	 * To add setting fields
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'wc_elex_ppct_discount_settings', $settings );
}
?>
<?php
add_action( 'woocommerce_update_options_elex_ppct_discount', 'elex_ppct_update_settings' );
function elex_ppct_update_settings() {
	if ( ! isset( $_POST['ppct-global-settings-fields-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['ppct-global-settings-fields-nonce'] ), basename( __FILE__ ) ) ) {
		return;
	}
	if ( isset( $_POST['elex_ppct_select_all_categories'] ) ) {
		update_option( 'elex_ppct_select_all_categories_id', 'yes' );
	} else {
		update_option( 'elex_ppct_select_all_categories_id', 'no' );
	}
	if ( isset( $_POST['elex_custom_text_selected_pages'] ) ) {
		update_option( 'elex_ppct_select_all_pages_value', 'yes' );
	} else {
		update_option( 'elex_ppct_select_all_pages_value', 'no' );
	}
	if ( isset( $_POST['elex_custom_text_selected_pages'] ) ) {
		update_option( 'elex_ppct_pages', ( map_deep( $_POST['elex_custom_text_selected_pages'], 'sanitize_text_field' ) ) );
	} else {
		update_option( 'elex_ppct_pages', array( 'no_page_selected' ) );
	}
	if ( isset( $_POST['elex_custom_text_categories'] ) ) {
		update_option( 'elex_ppct_categories', ( map_deep( $_POST['elex_custom_text_categories'], 'sanitize_text_field' ) ) );
	} else {
		update_option( 'elex_ppct_categories', array() );
	}
	woocommerce_update_options( elex_ppct_get_setting() );
}
new Elex_Product_Price_Discount_Setting();
