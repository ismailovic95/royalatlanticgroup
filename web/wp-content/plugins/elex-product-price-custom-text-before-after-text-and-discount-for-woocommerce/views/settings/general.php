<form method="POST">
	<?php 
		wp_nonce_field( 'save_settings', 'ppct_settings_nonce' );
		global $wp_roles;
		$all_roles                 = $wp_roles->role_names;
		$all_roles['unregistered'] = 'Unregistered';

	?>
	<div class="p-3">
		<div class="row">
			<div class="col-12">
				<table class="form-table">
					<tbody>
						<tr valign="top" class="">
							<th scope="row" class="titledesc ">Use Custom Text &amp; Discount</th>
							<td class="forminp forminp-checkbox ">
								<fieldset>
									<legend class="screen-reader-text"><span>Use Custom Text &amp; Discount</span></legend>
									<label for="elex_ppct_check_field">
										<input name="elex_ppct_check_field" id="elex_ppct_check_field" type="checkbox" class="" value="1" <?php echo ( get_option( 'elex_ppct_check_field' ) ) ? 'checked="checked"' : ''; ?>>
										Enable
									</label>
								</fieldset>
							</td>
						</tr>

						<?php 
						$selected_pages = array();
						if ( null !== get_option( 'elex_ppct_pages' ) ) {
							$selected_pages = get_option( 'elex_ppct_pages', array() );
						}
						?>

						<tr valign="top" class="elex_ppct_page_class">
							<th scope="row" class="titledesc">
								<label for="elex_ppct_page">Show Custom Text & Discount on</label>
								<span class="woocommerce-help-tip"></span>
							</th>
							<td class="forminp elex_ppct_page_td" id="elex_ppct_page_td_id">
								<div >
									<div>
										<input type="checkbox" name="elex_ppct_select_all_pages" id="elex_ppct_select_all_pages_id" <?php esc_attr_e( empty( get_option( 'elex_ppct_select_all_pages_value' ) ) || ( 'no' === get_option( 'elex_ppct_select_all_pages_value' ) ) ? '' : 'checked' ); ?>/>
										<strong><?php esc_attr_e( 'All Pages', 'elex-product-price-custom-text-and-discount' ); ?></strong>
									</div>
									<ul class="elex_pages" id="elex_ppct_pages" >
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

							/**
							 * Renders the categories.
							 *
							 * This function takes care of rendering the categories on the page.
							 *
							 * @param $parent_child_categories
							 * @param $parent_id
							 */
							function Render_categories( $parent_child_categories, $selected_categories, $filter, $parent_id = 0 ) {
								$child_categories = $parent_child_categories[ $parent_id ];
								$filter_array_name = '';
								if ( 'include_products' === $filter ) {
									$filter_array_name = 'general[limit_button_on_certain_products][include_products_by_category][]';
								} else if ( 'exclude_products' === $filter ) {
									$filter_array_name = 'general[exclude_products][by_category][]';
								} else {
									$filter_array_name = '';
								}
								?>
								
									<ul class="elex_categories" id="elex_ppct_categories_<?php esc_attr_e( $parent_id ); ?>">
								<?php
								foreach ( $child_categories as $child_category ) {

									?>
										<li>
											<input class="elex_custom_text_categories_<?php esc_attr_e( $filter ); ?>" 
												id="elex_category_<?php esc_attr_e( $child_category['category_id'] ); ?>" 
												type="checkbox" value="<?php esc_attr_e( $child_category['category_id'] ); ?>"
												name="<?php esc_attr_e( $filter_array_name ); ?>" <?php esc_attr_e( ( empty( $selected_categories ) ) ? '' : ( ( in_array( $child_category['category_id'], array_column( $selected_categories, 'id' ) ) ) ? 'checked' : '' ) ); ?>/> 
									<?php 
									esc_attr_e( $child_category['category_name'] );
									if ( isset( $parent_child_categories[ $child_category['category_id'] ] ) ) {
										Render_categories( $parent_child_categories, $selected_categories, $filter, $child_category['category_id'] );
									}
									?>
										</li>
									<?php
								}
								?>
									</ul>
								<?php
							}
							?>

						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="elex_ppct_prefix_field">Add Prefix <span class="woocommerce-help-tip" tabindex="0" aria-label="Add a prefix text with your product price"></span></label>
							</th>
							<td class="forminp forminp-textarea">
								<textarea name="elex_ppct_prefix_field" id="elex_ppct_prefix_field" style="width:250px" class="" placeholder=""><?php esc_attr_e( empty( get_option( 'elex_ppct_prefix_field' ) ) ? '' : get_option( 'elex_ppct_prefix_field' ) ); ?></textarea>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="elex_ppct_suffix_field">Add Suffix <span class="woocommerce-help-tip" tabindex="0" aria-label="Add a suffix text with your product price."></span></label>
							</th>
							<td class="forminp forminp-textarea">
								<textarea name="elex_ppct_suffix_field" id="elex_ppct_suffix_field" style="width:250px" class="" placeholder=""><?php esc_attr_e( empty( get_option( 'elex_ppct_suffix_field' ) ) ? '' : get_option( 'elex_ppct_suffix_field' ) ); ?></textarea>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="elex_ppct_discount_type">Discount Type <span class="woocommerce-help-tip" tabindex="0" aria-label="Specify the type you want to apply. This discount will be applied to all products."></span></label>
							</th>
							<td class="forminp forminp-select">
								<select name="elex_ppct_discount_type" id="elex_ppct_discount_type" style="width:250px;"  tabindex="-1" aria-hidden="true">
									<option style="font-size:18px;" value="no-discount" <?php esc_attr_e( ( get_option( 'elex_ppct_discount_type' ) === 'no-discount' ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'No Discount', 'elex-product-price-custom-text-and-discount' ); ?></option>
									<option style="font-size:18px;" value="amount" <?php esc_attr_e( ( get_option( 'elex_ppct_discount_type' ) === 'amount' ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Amount (' . get_woocommerce_currency() . ')', 'elex-product-price-custom-text-and-discount' ); ?></option>
									<option style="font-size:18px;" value="percent" <?php esc_attr_e( ( get_option( 'elex_ppct_discount_type' ) === 'percent' ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Percentage (%)', 'elex-product-price-custom-text-and-discount' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top" class="<?php esc_attr_e( ( get_option( 'elex_ppct_discount_type' ) === 'no-discount' ) ? 'd-none' : '' ); ?>" >
							<th scope="row" class="titledesc">
								<label for="elex_ppct_discount_amount">Discount Value<span class="woocommerce-help-tip" tabindex="0" aria-label="Specify the discount you want to apply. This discount will be applied to all products."></span></label>
							</th>
							<td class="forminp forminp-number">
								<input name="elex_ppct_discount_amount" id="elex_ppct_discount_amount" type="number" style="width:250px" value="<?php esc_attr_e( empty( get_option( 'elex_ppct_discount_amount' ) ) ? '' : get_option( 'elex_ppct_discount_amount' ) ); ?>" class="" placeholder="0" step="any" min="0" fdprocessedid="j0yisl">                             
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>

	<!-- filter Products -->
	<div class="elex-ppct-filter-products">
		<div class="px-3 ">
			<h5 class="fs-4 text-dark fw-bold"><?php esc_html_e( 'Filter Products', 'elex-product-price-custom-text-and-discount' ); ?></h5>
		</div>

		<div class="bg-warning bg-opacity-10 p-2  mb-3 elex-ppct-warning">
			<small style="font-weight: 500;">
				<?php
				esc_html_e(
					'By default, the custom text and discount will be applied to all products. Use the filter options below to exclude specific products, apply it selectively based on user roles, or customize the application further.'
				);
				?>
			</small>
		</div>
		<div class=" px-3 ">

			<!-- Limit Quote Button to Certain Products -->
			<div class="row align-items-center elex-ppct-check-sec ">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 d-flex align-items-baseline gap-2 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0 fs-small"><?php esc_html_e( 'Include Products', 'elex-product-price-custom-text-and-discount' ); ?></h6>
								
							</div>
							<sup class="text-success">[Premium!]</sup>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input id="limit_product_is_enabled" onchange="" type="checkbox"
										name="general[limit_button_on_certain_products][enabled]" value=""
										<?php echo ( isset( $settings['general']['limit_button_on_certain_products']['enabled'] ) && ( true === $settings['general']['limit_button_on_certain_products']['enabled'] ) ) ? 'checked' : ''; ?>
										class="elex-ppct-check-sec-input" unchecked disabled>
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the products for which product price custom text and discount settings should
                                        be applied. Rest of the products will be excluded
                                        automatically.',
											'elex-product-price-custom-text-and-discount'
										);
										?>
									</small>
								</div>
							</div>
						</div>
					</div>

					<!-- show sub input when input checked -->
					<div class="elex-ppct-check-content-limit-product">
						<!-- Include Products By Category -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small">
										<?php esc_html_e( 'Include Products By Category', 'elex-product-price-custom-text-and-discount' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the product categories for which you'd like to display the custom text and discount settings.">

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div style="border:1px solid #8c8f94; border-radius:4px; padding:5px; min-width: 250px; width:250px;margin-left: 12px;">
									<div>
										<input type="checkbox" name="general[limit_button_on_certain_products][select_all]" id="elex_ppct_select_all_categories_id_include_products" <?php esc_attr_e( ! empty( $include_products_select_all ) && $include_products_select_all ? 'checked' : '' ); ?>/>
										<strong><?php esc_attr_e( 'Select All Categories', 'elex-product-price-custom-text-and-discount' ); ?></strong>
										<span style="height: 1px;width:100%;display:block;margin: 3px 0;overflow: hidden;background-color: #8c8f94;"></span>
									</div>
									
									<div style="border-radius:4px; padding:5px; max-height:150px; overflow:auto; width:auto;">
										<?php Render_categories( $parent_child_categories, $include_products_by_category, 'include_products' ); ?>
									</div>
							</div>
						</div>

						<!-- Include product by Name -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Include Products By Name', 'elex-product-price-custom-text-and-discount' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the products for which you'd like to display the custom text and discount settings.">

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select
											name="general[limit_button_on_certain_products][include_products_by_name][]"
											style="width:100% !important"
											class="products_by_name include_prod_by_name  form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $include_products_by_name as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<!-- Include product by tags -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Include Products By Tags', 'elex-product-price-custom-text-and-discount' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the product tags for which you'd like to display the custom text and discount settings.">
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select
											name="general[limit_button_on_certain_products][include_products_by_tag][]"
											style="width:100% !important"
											class="products_by_tag include_prod_by_tag  form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $include_products_by_tag as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Exclude Product -->
			<div class="row align-items-center elex-ppct-check-sec">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 d-flex align-items-baseline gap-2 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0 fs-small"><?php esc_html_e( 'Exclude Product', 'elex-product-price-custom-text-and-discount' ); ?></h6>
							</div>
							<sup class="text-success">[Premium!]</sup>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input id="exclude_product_enabled" type="checkbox"
										name="general[exclude_products][enabled]"
										<?php echo ( isset( $settings['general']['exclude_products']['enabled'] ) && ( true === $settings['general']['exclude_products']['enabled'] ) ) ? 'checked' : ''; ?>
										value="" class="elex-ppct-check-sec-input-exclude-product" unchecked disabled>
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											"Select the product categories for which you'd like to exclude the custom text and discount settings.",
											'elex-product-price-custom-text-and-discount.'
										);
										?>
									</small>
								</div>
							</div>
						</div>
					</div>

					<!-- show sub input when input checked -->
					<div class="elex-ppct-check-content-exclude-product">
						<!-- Exclude Products By Category -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small">
										<?php esc_html_e( 'Exclude Products By Category', 'elex-product-price-custom-text-and-discount' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the products categories for which you don't want to display the product price custom text and discount settings">
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div style="border:1px solid #8c8f94; border-radius:4px; padding:5px; min-width: 250px; width:250px;margin-left: 12px;">
									<div>
										<input type="checkbox" name="general[exclude_products][select_all]" id="elex_ppct_select_all_categories_id_exclude_products" <?php esc_attr_e( ! empty( $exclude_products_select_all ) && $exclude_products_select_all ? 'checked' : '' ); ?>/>
										<strong><?php esc_attr_e( 'Select All Categories', 'elex-product-price-custom-text-and-discount' ); ?></strong>
										<span style="height: 1px;width:100%;display:block;margin: 3px 0;overflow: hidden;background-color: #8c8f94;"></span>
									</div>
									
									<div style="border-radius:4px; padding:5px; max-height:150px; overflow:auto; width:auto;">
										<?php Render_categories( $parent_child_categories, $exclude_products_by_category, 'exclude_products' ); ?>
									</div>
							</div>
						</div>

						<!-- Exclude product by Name -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Exclude Products By Name', 'elex-product-price-custom-text-and-discount' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the products for which you'd like to exclude the custom text and discount settings.">
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[exclude_products][by_name][]"
											style="width:100% !important"
											class="products_by_name exclude_prod_by_name form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $exclude_products_by_name as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<!-- Exclude product by tags -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Exclude Products By Tags', 'elex-product-price-custom-text-and-discount' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the product tags for which you'd like to exclude the custom text and discount settings.">
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[exclude_products][by_tag][]" style="width:100% !important"
											class="products_by_tag  exclude_prod_by_tag form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $exclude_products_by_tag as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
												<?php
											}
											?>
										</select>

									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- Role Based Filter -->
			<div class="row align-items-center elex-ppct-check-sec ">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 d-flex align-items-baseline gap-2">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0 fs-small"><?php esc_html_e( 'Role Based Filter', 'elex-product-price-custom-text-and-discount' ); ?></h6>
							</div>
							<sup class="text-success">[Premium!]</sup>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input id="role_based_enabled" type="checkbox"
										name="general[role_based_filter][enabled]" value=""
										<?php echo ( isset( $settings['general']['role_based_filter']['enabled'] ) && ( true === $settings['general']['role_based_filter']['enabled'] ) ) ? 'checked' : ''; ?>
										class="elex-ppct-check-sec-input-role-based" unchecked disabled>
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the roles to which custom text and discount settings for products should be applied or excluded. Leaving it blank will include all roles.',
											'elex-product-price-custom-text-and-discount'
										);
										?>
									</small>
								</div>
							</div>
						</div>
					</div>
					<!-- show sub input when input checked -->
					<div class="elex-ppct-check-content-role-based">
						<!-- Include Roles -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Include Roles', 'elex-product-price-custom-text-and-discount' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the user roles for which you'd like to display the product price custom text and discount settings.">
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[role_based_filter][include_roles][]"
											style="width:100% !important"
											class="include_roles  form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $all_roles as $key => $role_name ) {
												if ( in_array( $role_name, $include_roles ) ) {
													?>
											<option value="<?php echo esc_html_e( $key ); ?>" selected>
													<?php echo esc_html_e( $role_name ); ?></option>
												<?php } else { ?>
											<option value="<?php echo esc_html_e( $key ); ?>">
													<?php echo esc_html_e( $role_name ); ?></option>
													<?php
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<!-- Exclude Roles -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0 fs-small"><?php esc_html_e( 'Exclude Roles', 'elex-product-price-custom-text-and-discount' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="Select the user roles for which you'd like to exclude the product price custom text and discount settings.">

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[role_based_filter][exclude_roles][]"
											style="width:100% !important"
											class="exclude_roles form-select border-2 border-secondary "
											multiple="true">
											<?php
											foreach ( $all_roles as $key => $role_name ) {
												if ( in_array( $role_name, $exclude_roles ) ) {
													?>
											<option value="<?php echo esc_html_e( $key ); ?>" selected>
													<?php echo esc_html_e( $role_name ); ?></option>
												<?php } else { ?>
											<option value="<?php echo esc_html_e( $key ); ?>">
													<?php echo esc_html_e( $role_name ); ?></option>
													<?php
												}
											}
											?>
										</select>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="px-3">
			<button name="submit" type="submit"
				class="general_setting_save_chages btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-product-price-custom-text-and-discount' ); ?></button>
		</div>
</form>
