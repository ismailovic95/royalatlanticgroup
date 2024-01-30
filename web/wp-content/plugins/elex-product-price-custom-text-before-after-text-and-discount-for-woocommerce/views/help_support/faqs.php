<div class="accordion accordion-flush" id="accordionFlushExample">
	<div class="accordion-item mb-3 border-0 bg-light">
		<h2 class="accordion-header" id="flush-headingOne">
			<button class="accordion-button  collapsed fw-bold gap-2 align-items-start bg-transparent text-dark " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
				<div><?php esc_html_e( 'Q1.', 'elex-product-price-custom-text-and-discount' ); ?></div>
				<div><?php esc_html_e( 'Is it possible to add custom text for a single product?', 'elex-product-price-custom-text-and-discount' ); ?>
				</div>
			</button>
		</h2>
		<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body" style="padding-left: 50px;"><?php esc_html_e( 'Yes, the plugin allows you to add custom text for a single product by selecting that product in the included product list or by adding custom text at an individual product level.', 'elex-product-price-custom-text-and-discount' ); ?></small></div>
		</div>
	</div>
	<div class="accordion-item mb-3 border-0 bg-light">
		<h2 class="accordion-header" id="flush-headingTwo">
			<button class="accordion-button collapsed fw-bold gap-2 align-items-start bg-transparent text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
				<div><?php esc_html_e( 'Q2.', 'elex-product-price-custom-text-and-discount' ); ?></div>
				<div><?php esc_html_e( ' Is it possible to assign different custom text values for different products?', 'elex-product-price-custom-text-and-discount' ); ?>
				</div>
			</button>
		</h2>
		<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body" style="padding-left: 50px;">
			<?php 
			esc_html_e(
				'Yes, you can achieve this by assigning distinct custom text values at each product level.',
				'elex-product-price-custom-text-and-discount'
			); 
			?>
			</small></div>
		</div>
	</div>
	<div class="accordion-item mb-3  border-0  bg-light">
		<h2 class="accordion-header" id="flush-headingThree">
			<button class="accordion-button collapsed fw-bold gap-2 align-items-start bg-transparent text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
				<div><?php esc_html_e( 'Q3.', 'elex-product-price-custom-text-and-discount' ); ?></div>
				<div>
				<?php 
				esc_html_e(
					'What happens if I include and exclude the same product?',
					'elex-product-price-custom-text-and-discount'
				); 
				?>
				</div>
			</button>
		</h2>
		<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body" style="padding-left: 50px;">
				<?php 
				esc_html_e(
					'In such a case, exclusion takes priority, and the product will be excluded from applying the global settings.',
					'elex-product-price-custom-text-and-discount'
				); 
				?>
				</small></div>
		</div>
	</div>

	<div class="accordion-item mb-3  border-0  bg-light">
		<h2 class="accordion-header" id="flush-headingFour">
			<button class="accordion-button collapsed fw-bold gap-2 align-items-start bg-transparent text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
				<div><?php esc_html_e( 'Q4.', 'elex-product-price-custom-text-and-discount' ); ?></div>
				<div>
				<?php 
				esc_html_e(
					'Can I customize the frontend text font for custom text?',
					'elex-product-price-custom-text-and-discount'
				); 
				?>
				</div>
			</button>
		</h2>
		<div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body" style="padding-left: 50px;">
				<?php 
				esc_html_e(
					'Absolutely. You can modify the font family, font size, and color of the text through the customization tab.',
					'elex-product-price-custom-text-and-discount'
				); 
				?>
				</small></div>
		</div>
	</div>
</div>

