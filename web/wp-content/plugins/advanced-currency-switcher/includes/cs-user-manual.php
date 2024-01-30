<?php
/**
 * User Manual for how to use plugin.
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

?>
<html>
<body>
<h2> <?php esc_html_e( 'Welcome to Currency Switcher!', 'advanced-currency-switcher' ); ?> </h2>

<p> <?php esc_html_e( 'The Currency Switcher plugin is built to help you add a currency switcher anywhere on your website. Quickly manage settings and paste the shortcode in the desired position!', 'advanced-currency-switcher' ); ?> </p>

<h3> <?php esc_html_e( 'Getting Started', 'advanced-currency-switcher' ); ?> </h3>

<p><h4> <?php esc_html_e( 'You can set this up in 4 easy steps!', 'advanced-currency-switcher' ); ?> </h4> </p>

<ul>

	<li> <?php esc_html_e( '1. Select the conversion method - Manual or Open Exchange Rate API', 'advanced-currency-switcher' ); ?> </li>


	<p> <?php esc_html_e( 'Manual Conversion Rate - You will have to enter a base conversion rate and other currency values manually.', 'advanced-currency-switcher' ); ?>
	</p>
		<p> 
		<?php
		esc_html_e(
			'Open Exchange Rate API - You can authenticate your Open Exchange Rate API and fetch real-time Exchange Rates from it.
',
			'advanced-currency-switcher'
		);
		?>
	</p>

	<li> <?php esc_html_e( '2. Enter the required fields depending on the method you pick.', 'advanced-currency-switcher' ); ?></li>
	<li> <?php esc_html_e( '3. Select the Switcher Style and Number Format.', 'advanced-currency-switcher' ); ?></li>
	<li> <?php esc_html_e( '4. Copy the shortcodes from below and paste it on the page.', 'advanced-currency-switcher' ); ?></li>
</ul>
<hr style="border-bottom: dotted 1px #000" />
<h2> <?php esc_html_e( 'Shortcodes', 'advanced-currency-switcher' ); ?> </h2>

<ul>

	<li> <h4><?php esc_html_e( 'Shortcode for Currency switcher', 'advanced-currency-switcher' ); ?></h4> </li>

	<pre><code>[currency value=""]</code></pre>

	<p><?php esc_html_e( 'This shortcode lets you display field within which the original and the converted price will be seen.', 'advanced-currency-switcher' ); ?> <br><?php esc_html_e( 'Enter the cost (numerical value) of the product within the inverted commas (“”). For example, if the cost of a product is $100, the shortcode will be', 'advanced-currency-switcher' ); ?><b> [currency value=”100”]. </b>
	</p>

	<li><h4><?php esc_html_e( 'Shortcode for Currency switcher type', 'advanced-currency-switcher' ); ?> </h4> </li>

	<pre><code>[currency-switch]</code></pre>

	<p> <?php esc_html_e( 'Copy and paste this shortcode in the place you wish to add the switcher on the page.', 'advanced-currency-switcher' ); ?>
	</p>
	<p> <?php esc_html_e( 'The Global Settings allow you to select the switcher type you wish to use. This can either be a drop down menu or a button.', 'advanced-currency-switcher' ); ?>
	</p>

</ul>
<hr style="border-bottom: dotted 1px #000" />
<h2> <?php esc_html_e( 'Disclaimer', 'advanced-currency-switcher' ); ?> </h2>

<p> <?php esc_html_e( 'The accuracy of the currency may vary from time to time. This depends on the API you are using for the plugin. Since these values come from the exchange rate detectors, we are not responsible for the values by them.', 'advanced-currency-switcher' ); ?>
</p>

</body>
</html>
