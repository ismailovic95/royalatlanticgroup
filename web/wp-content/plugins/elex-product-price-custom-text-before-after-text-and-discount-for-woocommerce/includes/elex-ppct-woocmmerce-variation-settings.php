<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 

add_filter( 'woocommerce_product_data_tabs', 'elex_ppct_basic_custom_product_tab', 10, 1 );
add_action( 'woocommerce_admin_field_elex_ppct_custom_fields_suffix_checkbox', 'elex_ppct_basic_custom_field_suffix' );

function elex_ppct_basic_custom_field_suffix( $value ) {
  global $post;
	$product_info = wc_get_product( $post->ID );
	$suffix_checkbox = $product_info->get_meta( 'elex_ppct_custom_fields_suffix_checkbox' );
	?>
	<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php esc_attr_e( $value['id'] . '_0' ); ?>"><?php esc_attr_e( $value['title'] ); ?></label>
	</th>
	<td class="forminp elex_ppct_suffix_td" id="elex_ppct_suffix_td_id">
		<div>
			<div class="toggle-box mb-2">
				<p><?php echo esc_html_e( 'Use ', 'elex-product-price-custom-text-and-discount' ); ?></p>
				<div class="switch-box">
					<input type="checkbox" hidden="hidden" id="<?php esc_attr_e( $value['title'] ); ?>" name="elex_ppct_custom_fields_suffix_checkbox_name"
						<?php
						if ( 'yes' === $suffix_checkbox ) {
							?>
							checked=""
							<?php
						}
						?>
							value="<?php $suffix_checkbox; ?>"
					>
					<label class="switch" for="<?php esc_attr_e( $value['title'] ); ?>"></label>
				</div>
			</div>
		</div>
	 </td>
</tr>
	<?php
}

function elex_ppct_basic_custom_product_tab( $default_tabs ) {
	$default_tabs['custom_tab'] = array(
		'label'    => __( 'Price Text & Discount ', 'elex-product-price-custom-text-and-discount' ),
		'target'   => 'elex_ppct_custom_tab_data',
		'priority' => 60,
		'class'    => array(),
	);
	return $default_tabs;
}

add_action( 'woocommerce_product_data_panels', 'elex_ppct_custom_tab_data_basic' );
function elex_ppct_custom_tab_data_basic() {
  global $post;

	$product_info = wc_get_product( $post->ID );

	echo '<div id="elex_ppct_custom_tab_data" class="panel woocommerce_options_panel">';
	echo"<h2 style='text-align:center;'><b>Product Price Custom Text & Discount</b></h2>";
	$currency = get_woocommerce_currency_symbol();
	$args     = array(
		'id'          => 'elex_ppct_custom_fields_checkbox',
		'label'       => __( 'Use Custom Text & Discount for this product', 'elex-product-price-custom-text-and-discount' ),
		'description' => __( 'Check this box to use Custom Text & Discount for this product', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip' => true,
		'value'       => ( $product_info->get_meta( 'elex_ppct_custom_fields_checkbox' ) ) ? $product_info->get_meta( 'elex_ppct_custom_fields_checkbox' ) : 'no',
	);
	woocommerce_wp_checkbox( $args );
	?>
<div id="elex_ppct_display_fields">
	<?php
	wp_nonce_field( basename( __FILE__ ), 'ppct-custom-fields-nonce' );

	$args     = array(
		'id'          => 'elex_ppct_custom_fields_prefix_checkbox',
		'name'        => 'elex_ppct_custom_fields_prefix_checkbox_name',
		'label'       => __( 'Use Custom Prefix', 'elex-product-price-custom-text-and-discount' ),
		'description' => __( 'Check this box to use custom prefix for this product', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip'    => true,
		'value'       => ( $product_info->get_meta( 'elex_ppct_custom_fields_prefix_checkbox' ) ) ? $product_info->get_meta( 'elex_ppct_custom_fields_prefix_checkbox' ) : 'no',
	);
	woocommerce_wp_checkbox( $args );
	$args = array(
		'id'          => 'elex_ppct_custom_fields_prefix',
		'name'        => 'elex_ppct_custom_fields_prefix_name',
		'label'       => __( 'Custom Prefix', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip'    => true,
		'description' => __( 'Add a custom prefix text with your product price for this product.', 'elex-product-price-custom-text-and-discount' ),
		'placeholder' => __( 'Enter custom prefix for this product', 'elex-product-price-custom-text-and-discount' ),
		'value'       => ( $product_info->get_meta( 'elex_ppct_custom_fields_prefix' ) ) ? $product_info->get_meta( 'elex_ppct_custom_fields_prefix' ) : ' ',
	);
	woocommerce_wp_textarea_input( $args );

	$args     = array(
		'id'   => 'elex_ppct_custom_fields_suffix_checkbox',
		'name' => 'elex_ppct_custom_fields_suffix_checkbox_name',
		'label' => __( 'Use Custom Suffix', 'elex-product-price-custom-text-and-discount' ),
		'description' => __( 'Check this box to use custom suffix for this product', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip' => true,
	);
	woocommerce_wp_checkbox( $args );
	$args = array(
		'id'          => 'elex_ppct_custom_fields_suffix',
		'name'        => 'elex_ppct_custom_fields_suffix_name',
		'label'       => __( 'Custom Suffix', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip'    => true,
		'placeholder' => __( 'Enter custom suffix for this product', 'elex-product-price-custom-text-and-discount' ),
		'description' => __( 'Add a custom suffix text with your product price for this product.', 'elex-product-price-custom-text-and-discount' ),
		'value'       => ( $product_info->get_meta( 'elex_ppct_custom_fields_suffix' ) ) ? $product_info->get_meta( 'elex_ppct_custom_fields_suffix' ) : ' ',
	);
	woocommerce_wp_textarea_input( $args );

	$args = array(
		'id'          => 'elex_ppct_custom_fields_discount_type_checkbox',
		'name'        => 'elex_ppct_custom_fields_discount_type_checkbox_name',
		'label'       => __( 'Use Custom Discount', 'elex-product-price-custom-text-and-discount' ),
		'description' => __( 'Check this box to use custom discount for this product', 'elex-product-price-custom-text-and-discount' ),
		'desc_tip' => true, 
	);
	woocommerce_wp_checkbox( $args );
	$args = array(
		'type' => 'select',
		'name' => 'elex_ppct_custom_fields_discount_type_name',
		'label'       => __( 'Custom Discount Type', 'elex-product-price-custom-text-and-discount' ),
		'desc' => __( 'Specify the type you want to apply. This discount will be applied to all products.', 'elex-product-price-custom-text-and-discount' ),
		'class' => 'chosen_select',
		'id'      => 'elex_ppct_discount_type',
		'options' => array(
			'amount' => __( 'Amount', 'elex-product-price-custom-text-and-discount' ),
			'percent' => __( 'Percent', 'elex-product-price-custom-text-and-discount' ),
		),
		'desc_tip' => true,
		'value'       => ( $product_info->get_meta( 'elex_ppct_discount_type' ) ) ? $product_info->get_meta( 'elex_ppct_discount_type' ) : ' ',
	);
	woocommerce_wp_select( $args );
	$args = array(
		'id'          => 'elex_ppct_discount_amount',
		'name'        => 'elex_ppct_custom_fields_discount_amount_name',
		'label'       => __( 'Custom Discount', 'elex-product-price-custom-text-and-discount' ),
		'placeholder' => __( 'Enter custom discount for this product', 'elex-product-price-custom-text-and-discount' ),
		'type'        => 'number',
		'custom_attributes' => array(
			'step' => 'any',
			'min' => 0,
		),
		'desc_tip'    => true,
		'description' => __( 'Specify the discount you want to apply. This discount will be applied to this particular product.', 'elex-product-price-custom-text-and-discount' ),
		'value'       => ( $product_info->get_meta( 'elex_ppct_discount_amount' ) ) ? $product_info->get_meta( 'elex_ppct_discount_amount' ) : ' ',
	);
	woocommerce_wp_text_input( $args );
	?>
</div>
<hr>
<div style="width:640px;
	height:50px;
	border: 1px solid;
	margin: 20px;">
	<h2>For setting up discount on individual product variations go to the specific variation and add the discount. </h2>
</div>
	<?php
	echo '</div>';
	?>
	<script type="text/javascript" >
	jQuery(function($){

		// All fields checkbox
		const elex_ppct_custom_fields_checkbox = jQuery('#elex_ppct_custom_fields_checkbox');
		elex_ppct_custom_fields_checkbox.click(function(){
			var value=jQuery(this).is(':checked');
			if(value === true ){
				jQuery('#elex_ppct_display_fields').show();
			} else {
				jQuery('#elex_ppct_display_fields').hide();
			}
		});
		if (elex_ppct_custom_fields_checkbox.is(":checked")) {
			jQuery('#elex_ppct_display_fields').show();
		} else {
			jQuery('#elex_ppct_display_fields').hide();
		}

		// Prefix checkbox
		const elex_ppct_custom_fields_prefix_checkbox = jQuery('#elex_ppct_custom_fields_prefix_checkbox');
		elex_ppct_custom_fields_prefix_checkbox.on('change', function() {
			if(this.checked) {
				jQuery('#elex_ppct_custom_fields_prefix').closest('p').show();
			} else {
				jQuery('#elex_ppct_custom_fields_prefix').closest('p').hide();
			}
		});
		if( elex_ppct_custom_fields_prefix_checkbox.is(':checked') ) {
			jQuery('#elex_ppct_custom_fields_prefix').closest('p').show();
		} else {
			jQuery('#elex_ppct_custom_fields_prefix').closest('p').hide();
		}

		// Suffix checkbox
		const elex_ppct_custom_fields_suffix_checkbox = jQuery('#elex_ppct_custom_fields_suffix_checkbox');
		elex_ppct_custom_fields_suffix_checkbox.on('change', function() {
			if(this.checked) {
				jQuery('.elex_ppct_custom_fields_suffix_field ').show();
			} else {
				jQuery('.elex_ppct_custom_fields_suffix_field ').hide();
			}
		});
		if ( elex_ppct_custom_fields_suffix_checkbox.is(':checked') ) {
			jQuery('.elex_ppct_custom_fields_suffix_field ').show();
		} else {
			jQuery('.elex_ppct_custom_fields_suffix_field ').hide();
		}

		// Discount type checkbox
		const elex_ppct_custom_fields_discount_type_checkbox = jQuery('#elex_ppct_custom_fields_discount_type_checkbox');
		elex_ppct_custom_fields_discount_type_checkbox.on('change', function() {
			if(this.checked) {
				jQuery('#elex_ppct_discount_type').closest('p').show();
				jQuery('#elex_ppct_discount_amount').closest('p').show();
			} else {
				jQuery('#elex_ppct_discount_type').closest('p').hide();
				jQuery('#elex_ppct_discount_amount').closest('p').hide();
			}
		});
		if ( elex_ppct_custom_fields_discount_type_checkbox.is(':checked') ) {
			jQuery('#elex_ppct_discount_type').closest('p').show();
			jQuery('#elex_ppct_discount_amount').closest('p').show();
		} else {
			jQuery('#elex_ppct_discount_type').closest('p').hide();
			jQuery('#elex_ppct_discount_amount').closest('p').hide();
		}
	});

	</script>
	<?php
}
add_action( 'woocommerce_process_product_meta', 'elex_ppct_basic_save_fields', 10, 1 );

function elex_ppct_basic_save_fields( $post_id ) {
	if ( ! isset( $_POST['ppct-custom-fields-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['ppct-custom-fields-nonce'] ), basename( __FILE__ ) ) ) {
		return $post_id;
	}

	$checkbox = isset( $_POST['elex_ppct_custom_fields_checkbox'] ) ? 'yes' : 'no';

	$product_info = wc_get_product( $post_id );

	$product_info->update_meta_data( 'elex_ppct_custom_fields_checkbox', $checkbox );

	$prefix_checkbox = isset( $_POST['elex_ppct_custom_fields_prefix_checkbox_name'] ) ? 'yes' : 'no';

	$product_info->update_meta_data( 'elex_ppct_custom_fields_prefix_checkbox', $prefix_checkbox );

	$text_field1 = ! empty( $_POST['elex_ppct_custom_fields_prefix_name'] ) ? wp_kses_post( $_POST['elex_ppct_custom_fields_prefix_name'] ) : null;

	$product_info->update_meta_data( 'elex_ppct_custom_fields_prefix', $text_field1 );

	$suffix_checkbox = isset( $_POST['elex_ppct_custom_fields_suffix_checkbox_name'] ) ? 'yes' : 'no';

	$product_info->update_meta_data( 'elex_ppct_custom_fields_suffix_checkbox', $suffix_checkbox );

	$text_field3 = ! empty( $_POST['elex_ppct_custom_fields_suffix_name'] ) ? wp_kses_post( $_POST['elex_ppct_custom_fields_suffix_name'] ) : '';

	$product_info->update_meta_data( 'elex_ppct_custom_fields_suffix', $text_field3 );

	$discount_checkbox = isset( $_POST['elex_ppct_custom_fields_discount_type_checkbox_name'] ) ? 'yes' : 'no';

	$product_info->update_meta_data( 'elex_ppct_custom_fields_discount_type_checkbox', $discount_checkbox );

	$select_field2 = ! empty( $_POST['elex_ppct_custom_fields_discount_type_name'] ) ? sanitize_text_field( $_POST['elex_ppct_custom_fields_discount_type_name'] ) : '';

	$product_info->update_meta_data( 'elex_ppct_discount_type', $select_field2 );

	$text_field4 = ! empty( $_POST['elex_ppct_custom_fields_discount_amount_name'] ) ? sanitize_text_field( $_POST['elex_ppct_custom_fields_discount_amount_name'] ) : '';

	$product_info->update_meta_data( 'elex_ppct_discount_amount', $text_field4 );

	$product_info->save();
}

add_action( 'woocommerce_product_after_variable_attributes', 'elex_ppct_basic_add_custom_field_to_variations', 10, 3 );
function elex_ppct_basic_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
   global $post;
	$var_product = wc_get_product( $variation->ID );
	echo"<h2 style='text-align:center; font-size:20px;'>Product Price Custom Text & Discount For This Variation</h2>";
	$use_custom_text_plugin = $var_product->get_meta( 'elex_ppct_variation_use_custom_text_plugin' );
	$use_prefix = $var_product->get_meta( 'elex_ppct_variation_use_prefix_post_meta' );
	$use_suffix = $var_product->get_meta( 'elex_ppct_variation_use_suffix_post_meta' );
	$use_discount = $var_product->get_meta( 'elex_ppct_variation_use_discount_post_meta' );
	?>
	<p class="form-row form-row-full options">
		<!-- Use Custom text Plugin -->
		<label class="tips" data-tip="<?php esc_attr_e( 'Use Custom text and Discount Plugin for this Variation', 'elex-product-price-custom-text-and-discount' ); ?>">
	<?php esc_html_e( 'Use Custom text and Discount Plugin', 'elex-product-price-custom-text-and-discount' ); ?>
			<input type="checkbox" class="checkbox" name="elex_ppct_variation_use_custom_text_plugin[<?php esc_attr_e( $variation->ID ); ?>]" id = "elex_ppct_variation_use_custom_text_plugin_<?php esc_attr_e( $variation->ID ); ?>" <?php esc_attr_e( ( empty( $use_custom_text_plugin ) || 'no' === $use_custom_text_plugin ) ? '' : 'checked' ); ?>  />
		</label>
		<!-- Use prefix -->
		<label class="tips" data-tip="<?php esc_attr_e( 'Use prefix for this variation.', 'elex-product-price-custom-text-and-discount' ); ?>">
	<?php esc_html_e( 'Use Custom Prefix', 'elex-product-price-custom-text-and-discount' ); ?>
			<input type="checkbox" class="checkbox" name="elex_ppct_variation_use_prefix[<?php esc_attr_e( $variation->ID ); ?>]" id = "elex_ppct_variation_use_prefix_id_<?php esc_attr_e( $variation->ID ); ?>" <?php esc_attr_e( ( empty( $use_prefix ) || 'no' === $use_prefix ) ? '' : 'checked' ); ?>  />
		</label>
		<!-- Use suffix -->
		<label class="tips" data-tip="<?php esc_attr_e( 'Use suffix for this variation.', 'elex-product-price-custom-text-and-discount' ); ?>">
	<?php esc_html_e( 'Use Custom Suffix', 'elex-product-price-custom-text-and-discount' ); ?>
			<input type="checkbox" class="checkbox" name="elex_ppct_variation_use_suffix[<?php esc_attr_e( $variation->ID ); ?>]" id = "elex_ppct_variation_use_suffix_id_<?php esc_attr_e( $variation->ID ); ?>" <?php esc_attr_e( ( empty( $use_suffix ) || 'no' === $use_suffix ) ? '' : 'checked' ); ?>  />
		</label>
		<!-- Use Discount -->
		<label class="tips" data-tip="<?php esc_attr_e( 'Use discount for this variation.', 'elex-product-price-custom-text-and-discount' ); ?>">
	<?php esc_html_e( 'Use Custom Discount', 'elex-product-price-custom-text-and-discount' ); ?>
			<input type="checkbox" class="checkbox" name="elex_ppct_variation_use_discount[<?php esc_attr_e( $variation->ID ); ?>]" id = "elex_ppct_variation_use_discount_id_<?php esc_attr_e( $variation->ID ); ?>" <?php esc_attr_e( ( empty( $use_discount ) || 'no' === $use_discount ) ? '' : 'checked' ); ?>  />
		</label>
	</p>
	<?php
	wp_nonce_field( basename( __FILE__ ), 'ppct-custom-fields-variation-nonce' );
	woocommerce_wp_textarea_input(
		array(
			'id'          => 'elex_ppct_variation_add_prefix_' . $variation->ID,
			'name'        => 'elex_ppct_variation_add_prefix[' . $variation->ID . ']',
			'type'        => 'textarea',
			'label'       => __( 'Add Prefix', 'elex-product-price-custom-text-and-discount' ),
			'desc_tip'    => true,
			'description' => __( 'Specify the prefix for this variation.', 'elex-product-price-custom-text-and-discount' ),
			'value'       => $var_product->get_meta( 'elex_ppct_variation_add_prefix' ),
		)
	);
	woocommerce_wp_textarea_input(
		array(
			'id'          => 'elex_ppct_variation_add_suffix_' . $variation->ID,
			'name'        => 'elex_ppct_variation_add_suffix[' . $variation->ID . ']',
			'type'        => 'textarea',
			'label'       => __( 'Add Suffix', 'elex-product-price-custom-text-and-discount' ),
			'desc_tip'    => true,
			'description' => __( 'Specify the suffix for this variation.', 'elex-product-price-custom-text-and-discount' ),
			'value'       => $var_product->get_meta( 'elex_ppct_variation_add_suffix' ),
		)
	);
	woocommerce_wp_select(
		array(
			'id'                => 'elex_ppct_discount_type_' . $variation->ID,
			'type'              => 'select',
			'label'             => __( 'Discount type', 'elex-product-price-custom-text-and-discount' ),
			'name'              => 'elex_ppct_discount_type[' . $variation->ID . ']',
			'class'             => 'choosen_select',
			'desc_tip'          => true,
			'description'       => __( 'Specify the discount type you want to apply. This discount will be applied to this particular product variation.', 'elex-product-price-custom-text-and-discount' ),
			'options' => array(
				'amount'  => __( 'Amount', 'elex-product-price-custom-text-and-discount' ),
				'percent' => __( 'Percent', 'elex-product-price-custom-text-and-discount' ),
			),
			'value'       => $var_product->get_meta( 'elex_ppct_discount_type' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_ppct_discount_amount_' . $variation->ID,
			'type'              => 'number',
			'name'              => 'elex_ppct_discount_amount[' . $variation->ID . ']',
			'label'             => __( 'Discount', 'elex-product-price-custom-text-and-discount' ),
			'custom_attributes' => array(
				'step' => '0.01',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'description'       => __( 'Specify the discount you want to apply. This discount will be applied to this particular product variation.', 'elex-product-price-custom-text-and-discount' ),
			'value'             => $var_product->get_meta( 'elex_ppct_discount_amount' ),
		)
	);
	?>
	<script>
		jQuery('#elex_ppct_variation_use_custom_text_plugin_<?php esc_attr_e( $variation->ID ); ?>').on('change',function() {
			if(this.checked){
				jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
				jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
				jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
				jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
				jQuery('#elex_ppct_variation_use_prefix_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', true);
				jQuery('#elex_ppct_variation_use_suffix_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', true);
				jQuery('#elex_ppct_variation_use_discount_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', true);
			} else {
				jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
				jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
				jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
				jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
				jQuery('#elex_ppct_variation_use_prefix_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', false);
				jQuery('#elex_ppct_variation_use_suffix_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', false);
				jQuery('#elex_ppct_variation_use_discount_id_<?php esc_attr_e( $variation->ID ); ?>').prop('checked', false);
			}
		});
		if ( jQuery('#elex_ppct_variation_use_custom_text_plugin_<?php esc_attr_e( $variation->ID ); ?>').is(':checked') ) {
			jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').show();
		} else {
			jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').hide();
		}

		// Hide and Show prefix field
		jQuery('#elex_ppct_variation_use_prefix_id_<?php esc_attr_e( $variation->ID ); ?>').on('change',function() {
			if(this.checked){
				jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			} else {
				jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			}
		});
		if( jQuery('#elex_ppct_variation_use_prefix_id_<?php esc_attr_e( $variation->ID ); ?>').is(':checked') ) {
			jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
		} else {
			jQuery('.elex_ppct_variation_add_prefix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
		}

		// Hide and Show suffix field.
		jQuery('#elex_ppct_variation_use_suffix_id_<?php esc_attr_e( $variation->ID ); ?>').on('change',function() {
			if(this.checked){
				jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			} else {
				jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			}
		});
		if( jQuery('#elex_ppct_variation_use_suffix_id_<?php esc_attr_e( $variation->ID ); ?>').is(':checked') ) {
			jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
		} else {
			jQuery('.elex_ppct_variation_add_suffix_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
		}

		// Hide and Show discount field.
		jQuery('#elex_ppct_variation_use_discount_id_<?php esc_attr_e( $variation->ID ); ?>').on('change',function() {
			if(this.checked){
				jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
				jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').show();
			} else {
				jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
				jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			}
		});
		if ( jQuery('#elex_ppct_variation_use_discount_id_<?php esc_attr_e( $variation->ID ); ?>').is(':checked') ) {
			jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').show(); 
			jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').show();
		} else {
			jQuery('.elex_ppct_discount_type_<?php esc_attr_e( $variation->ID ); ?>_field').hide(); 
			jQuery('.elex_ppct_discount_amount_<?php esc_attr_e( $variation->ID ); ?>_field').hide();
		}
	</script>
	<?php
}

// -----------------------------------------
// 2. Save custom field on product variation save

add_action( 'woocommerce_save_product_variation', 'elex_ppct_basic_save_custom_field_variations', 10, 2 );
function elex_ppct_basic_save_custom_field_variations( $post_id ) {
	if ( ! isset( $_POST['ppct-custom-fields-variation-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['ppct-custom-fields-variation-nonce'] ), basename( __FILE__ ) ) ) {
		return false;
	}

	$product_info = wc_get_product( $post_id );

	$use_custon_text_plugin = isset( $_POST['elex_ppct_variation_use_custom_text_plugin'] ) ? 'yes' : 'no';
	$product_info->update_meta_data( 'elex_ppct_variation_use_custom_text_plugin', $use_custon_text_plugin );

	$use_prefix = isset( $_POST['elex_ppct_variation_use_prefix'][ $post_id ] ) ? 'yes' : 'no';
	$product_info->update_meta_data( 'elex_ppct_variation_use_prefix_post_meta', $use_prefix );

	$use_suffix = isset( $_POST['elex_ppct_variation_use_suffix'][ $post_id ] ) ? 'yes' : 'no';
	$product_info->update_meta_data( 'elex_ppct_variation_use_suffix_post_meta', $use_suffix );

	$use_discount = isset( $_POST['elex_ppct_variation_use_discount'][ $post_id ] ) ? 'yes' : 'no';
	$product_info->update_meta_data( 'elex_ppct_variation_use_discount_post_meta', $use_discount );
	 
	$custom_field_prefix = isset( $_POST['elex_ppct_variation_add_prefix'][ $post_id ] ) ? wp_kses_post( $_POST['elex_ppct_variation_add_prefix'][ $post_id ] ) : '';
	$product_info->update_meta_data( 'elex_ppct_variation_add_prefix', $custom_field_prefix );

	$custom_field_prefix = isset( $_POST['elex_ppct_variation_add_suffix'][ $post_id ] ) ? wp_kses_post( $_POST['elex_ppct_variation_add_suffix'][ $post_id ] ) : '';
	$product_info->update_meta_data( 'elex_ppct_variation_add_suffix', $custom_field_prefix );

	$custom_field1 = isset( $_POST['elex_ppct_discount_type'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_ppct_discount_type'][ $post_id ] ) : '';
	$product_info->update_meta_data( 'elex_ppct_discount_type', $custom_field1 );

	$custom_field2 = isset( $_POST['elex_ppct_discount_amount'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_ppct_discount_amount'][ $post_id ] ) : '';
	$product_info->update_meta_data( 'elex_ppct_discount_amount', $custom_field2 );

	$product_info->save();
}
