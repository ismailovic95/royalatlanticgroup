<?php
/**
 * Class-cs-shortcode-value.php
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

/**
 * Custom modules
 */
if ( ! class_exists( 'CS_Currency_Shortcode' ) ) {

	/**
	 * Class define for currency convert shortcode.
	 *
	 * @class    CS_Currency_Shortcode
	 * @category PHP
	 * @package  Currency_Switcher
	 * @author   Display Name <ahemads@bsf.io>
	 * @license  https://brainstormforce.com
	 * @link     https://brainstormforce.com
	 */
	class CS_Currency_Shortcode {


		/**
		 * Constructor
		 */
		public function __construct() {
			add_shortcode( 'currency', array( $this, 'cswp_advance_currency_switch' ) );
		}

		/**
		 * Define Currency_Switcher.
		 *
		 * @param string $atts The text to be formatted.
		 *
		 * @since  1.0.0
		 * @return return ob_get_clean().
		 */
		public function cswp_advance_currency_switch( $atts ) {
			$attributes = shortcode_atts(
				array(
					'value' => '',
				),
				$atts
			);

			$getval        = (float) $attributes['value'];
			$price_convert = (float) $attributes['value'];
			ob_start();
			?>
			<!--  Create custom div and span for display price -->
			<div class="cs-converter-wrap" >
				<span class="cs-convertor-wrap-symbol" id="cswp_symbol"></span>
				<span id="cs-convertor-wrap" class="cs-convertor-wrap-data" value_convert="<?php echo esc_attr( $price_convert ); ?>">
					<?php
					echo esc_attr( $getval );
					?>
				</span>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	$cs_currency_shortcode = new CS_Currency_Shortcode();
}
