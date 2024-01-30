<style>
.woocommerce-save-button{
	display: none !important;
}

.btn{
	display: inline-block;
	padding: 6px 12px;
	font-size: 12px;
	font-weight: normal;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	-ms-touch-action: manipulation;
	touch-action: manipulation;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	background-image: none;
	border: 1px solid transparent;
	border-radius: 4px;
}
.elex_dp_wrapper {
	/* padding-left: 5%;
	padding-right: 5%; */
	/* background-color: red; */
	width: 85%;
	margin-top:2px;
	min-height: 50px;
	margin-right: 40px;
	padding-left:15%;
	z-index:1;
	right:0px;
	float:left;
	background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#fff), to(#f3f3f3), color-stop(.1,#fff));
	border: 4px solid #ccc;
	border-radius: 60px 5px;
	-webkit-border-radius: 60px 5px;
	box-shadow: 0px 0px 35px rgba(0, 0, 0, 0.1) inset;
	-webkit-box-shadow: 0px 0px 35px rgba(0, 0, 0, 0.1) inset;
}

.marketing_logos{
	width:100%;
	height: auto;
	border-radius: 10px;
}

.marketing_redirect_links{
	padding: 0px 2px !important;
	background-color: #337AB7 !important;
	color: white !important;
	height: 52px;
	font-weight: 600 !important;
	font-size: 18px !important;
	min-width: 210px;
}
  
.row {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	width: 100%;
	font-size: 15px;
	padding: 3%;
}

.col-md-4{
	display: flex;
	flex-direction: column;
	flex-basis: 100%;
	flex: 2;
}

.container {
	margin-top: 20px;
	margin-bottom:20px;
	height: 50px;
	position: relative;
}

.center {
	margin: 0;
	position: absolute;
	top: 50%;
	left: 40%;
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
}

.panel-heading {
	padding: 10px 15px;
	border-bottom: 1px solid transparent;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
}

.elex_dp_wrapper a {
	color: #337ab7;
	text-decoration: none;
	background-color: transparent;
}
.elex_dp_wrapper a:active {
	outline: 0;
}
.elex_dp_wrapper a:hover {
	color: #23527c;
	text-decoration: underline;
	outline: 0;
}
.elex_dp_wrapper a:focus {
	color: #23527c;
	text-decoration: underline;
	outline: 5px auto -webkit-focus-ring-color;
	outline-offset: -2px;
}

.elex_dp_wrapper small {
	font-size: 80%;
	font-size: 85%;
}

.elex_dp_wrapper img {
	vertical-align: middle;
	border: 0;
}


.elex_dp_wrapper button {
	-webkit-appearance: button;
	margin: 0;
	overflow: visible;
	font: inherit;
	font-family: inherit;
	font-size: inherit;
	line-height: inherit;
	color: inherit;
	text-transform: none;
	cursor: pointer;
}
.elex_dp_wrapper button::-moz-focus-inner {
	padding: 0;
	border: 0;
}
.elex_dp_wrapper input {
	margin: 0;
	font: inherit;
	font-family: inherit;
	font-size: inherit;
	line-height: normal;
	line-height: inherit;
	color: inherit;
}

.elex_dp_wrapper input::-moz-focus-inner {
	padding: 0;
	border: 0;
}

.elex_dp_wrapper button[disabled] {
	cursor: default;
}

.elex_dp_wrapper * {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.elex_dp_wrapper *:before {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.elex_dp_wrapper *:after {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.elex_dp_wrapper .img-responsive {
	display: block;
	max-width: 100%;
	height: auto;
}

.elex_dp_wrapper .img-rounded {
	border-radius: 6px;
}
.elex_dp_wrapper .img-thumbnail {
	display: inline-block;
	max-width: 100%;
	height: auto;
	padding: 4px;
	line-height: 1.42857143;
	background-color: #fff;
	border: 1px solid #ddd;
	border-radius: 4px;
	-webkit-transition: all .2s ease-in-out;
	-o-transition: all .2s ease-in-out;
	transition: all .2s ease-in-out;
}
.elex_dp_wrapper .img-circle {
	border-radius: 50%;
}

.elex_dp_wrapper h3 {
	margin-top: 20px;
	margin-bottom: 10px;
	font-family: inherit;
	font-size: 24px;
	font-weight: 500;
	line-height: 1.1;
	color: inherit;
}
.elex_dp_wrapper h3 small {
	font-size: 65%;
	font-weight: normal;
	line-height: 1;
	color: #777;
}
.elex_dp_wrapper h3 .small {
	font-size: 65%;
	font-weight: normal;
	line-height: 1;
	color: #777;
}

.elex_dp_wrapper h5 {
	margin-top: 10px;
	margin-bottom: 10px;
	font-family: inherit;
	font-size: 14px;
	font-weight: 500;
	line-height: 1.1;
	display:block;
	color: inherit;
}
.elex_dp_wrapper h5 small {
	font-size: 75%;
	font-weight: normal;
	line-height: 1;
	color: #777;
}
.elex_dp_wrapper h5 .small {
	font-size: 75%;
	font-weight: normal;
	line-height: 1;
	color: #777;
}


.elex_dp_wrapper .col-md-4 {
	position: relative;
	min-height: 1px;
	padding-right: 15px;
	padding-left: 15px;
}
  
.elex_dp_wrapper .btn:focus {
	color: #333;
	text-decoration: none;
	outline: 5px auto -webkit-focus-ring-color;
	outline-offset: -2px;
}
.elex_dp_wrapper .btn:active {
	background-image: none;
	outline: 0;
	-webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
	box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
}
.elex_dp_wrapper .btn:active:focus {
	outline: 5px auto -webkit-focus-ring-color;
	outline-offset: -2px;
}
.elex_dp_wrapper .btn:active.focus {
	outline: 5px auto -webkit-focus-ring-color;
	outline-offset: -2px;
}
.elex_dp_wrapper .btn:hover {
	color: #333;
	text-decoration: none;
}
div.col-md-12 {
	vertical-align: top;
	display: inline-block;
	text-align: center;
	width: 80%;
}
.elex_dp_wrapper {
	width: 100% !important;
	padding: 0 !important;
}
.text-center{
	text-align: center;
}
</style>
<center class="my-3 px-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class=" elex-license-like-img-container col-md-4">
								<a target="_blank" href="https://elextensions.com/plugin/elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin/">
									<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\ppct.png' ); ?>" class="marketing_logos">
								</a>
								<br>
							</div>
							<div class="col-md-5">
								<ul style="list-style-type:disc;">
									<p>Note: Basic version supports only few features.</p>
									<p style="color:red;"><strong>Your business is precious! Go premium with additional features!.</strong></p>
									<p style="text-align:left">
										 - All the features in the free version.<br>
										 - Filter products based on name, category, and roles.<br>
										 - Customize custom text font style, size, and color.<br>
										 - Premium Support!										<br>
									</p>
								</ul>
								<center> <a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin/" target="_blank" class="button button-primary">Documentation</a></center>
							</div>
						</div>
					</div>
				</div>
			</center>

<center style="margin-top: 20px;">
	<div class="panel-heading" style="background-color:#337AB7;color:white;">
		<h3 class="panel-title" style="color: white;padding: 5px 0px;margin: 0px"><strong><?php echo esc_html( 'ELEXtensions Plugins You May Be Interested In...' ); ?></strong></h3>
	</div>
</center>

<div class="elex_dp_wrapper">
	<div class="row">
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\AdvancedBulkEdit.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/bulk-edit-products-prices-attributes-for-woocommerce/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Advanced Bulk Edit Products, Prices & Attributes</a></h5>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\Dynamic Pricing & Discounts.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/dynamic-pricing-and-discounts-plugin-for-woocommerce/" data-wpel-link="internal" target="_blank">ELEX Dynamic Pricing and Discounts Plugin for WooCommerce</a></h5>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\gpf.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/woocommerce-google-product-feed-plugin/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Google Product Feed Plugin</a></h5>
				</div>
			</div>
		</div>
	</div>
	<div class="row">  
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\Catalog Mode.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/woocommerce-catalog-mode-wholesale-role-based-pricing/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Catalog Mode, Wholesale & Role Based Pricing</a></h5>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\Request a Quote.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/woocommerce-request-a-quote-plugin/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Request a Quote Plugin</a></h5>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="">
				<div class="">
					<img src="<?php echo esc_url( ELEX_PPCT_MAIN_IMG . '\wschat.png' ); ?>" class="marketing_logos">
				</div>
			</div>
			<div class="">
				<div class="">
					<h5 class="text-center"><a href="https://elextensions.com/plugin/wschat-wordpress-live-chat-plugin/" data-wpel-link="internal" target="_blank">WSChat – ELEX WordPress Live Chat Plugin</a></h5>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="text-center">
			<input type ="button" onclick='window.open("https://elextensions.com/product-category/plugins/", "_blank")' class="btn marketing_redirect_links" target="_blank" value="Browse All ELEXtensions Plugins">
		</div>
	</div>
</div>
