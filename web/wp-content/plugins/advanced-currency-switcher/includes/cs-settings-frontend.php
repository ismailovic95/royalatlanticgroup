<?php
/**
 * Setting Front End Doc comment
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

// Store form inputs values in variable.
$cswp_get_form_value = CS_Loader::cswp_load_all_data();

$cswp_basecurency = isset( $cswp_get_form_value['basecurency'] ) ? $cswp_get_form_value['basecurency'] : '';

$cswp_form_select_value = isset( $cswp_get_form_value['cswp_form_select'] ) ? $cswp_get_form_value['cswp_form_select'] : '';

$cswp_api_key = isset( $cswp_get_form_value['api_key'] ) ? $cswp_get_form_value['api_key'] : '';

$api_key_status = isset( $cswp_get_form_value['api_key_status'] ) ? $cswp_get_form_value['api_key_status'] : '';

$cswp_frequency_reload = isset( $cswp_get_form_value['frequency_reload'] ) ? $cswp_get_form_value['frequency_reload'] : '';

$cswp_button_type_value = isset( $cswp_get_form_value['cswp_button_type'] ) ? $cswp_get_form_value['cswp_button_type'] : '';

$cswp_decimal_place_value = isset( $cswp_get_form_value['decimalradio'] ) ? $cswp_get_form_value['decimalradio'] : '';


$cswp_usd_text = isset( $cswp_get_form_value['usd-text'] ) ? $cswp_get_form_value['usd-text'] : 'Change to USD';

$cswp_inr_text = isset( $cswp_get_form_value['inr-text'] ) ? $cswp_get_form_value['inr-text'] : 'Change to INR';

$cswp_eur_text = isset( $cswp_get_form_value['eur-text'] ) ? $cswp_get_form_value['eur-text'] : 'Change to EURO';

$cswp_aud_text = isset( $cswp_get_form_value['aud-text'] ) ? $cswp_get_form_value['aud-text'] : 'Change to AUD';

$cswp_inr_symbol = isset( $cswp_get_form_value['inr-symbol'] ) ? $cswp_get_form_value['inr-symbol'] : '&#8377;';
$cswp_eur_symbol = isset( $cswp_get_form_value['eur-symbol'] ) ? $cswp_get_form_value['eur-symbol'] : '&#8364;';
$cswp_aud_symbol = isset( $cswp_get_form_value['aud-symbol'] ) ? $cswp_get_form_value['aud-symbol'] : '&#36;';
$cswp_usd_symbol = isset( $cswp_get_form_value['usd-symbol'] ) ? $cswp_get_form_value['usd-symbol'] : '&#36;';


// Store Manual rate values in variable.
$cswp_manualrate = CS_Loader::cswp_load_manual_data();

$cswp_usd_rate = isset( $cswp_manualrate['usd_rate'] ) ? $cswp_manualrate['usd_rate'] : '1';

$cswp_inr_rate = isset( $cswp_manualrate['inr_rate'] ) ? $cswp_manualrate['inr_rate'] : '69.45';

$cswp_eur_rate = isset( $cswp_manualrate['eur_rate'] ) ? $cswp_manualrate['eur_rate'] : '0.89';

$cswp_aud_rate = isset( $cswp_manualrate['aud_rate'] ) ? $cswp_manualrate['aud_rate'] : '1.45';



// Store OpenExchangeRate value in a variable.
$cswp_apirate_values = CS_Loader::cswp_load_apirate_values_data();

$apitext_inr = isset( $cswp_apirate_values['inr'] ) ? $cswp_apirate_values['inr'] : '';

$apitext_usd = isset( $cswp_apirate_values['usd'] ) ? $cswp_apirate_values['usd'] : '';

$apitext_eur = isset( $cswp_apirate_values['eur'] ) ? $cswp_apirate_values['eur'] : '';

$apitext_aud = isset( $cswp_apirate_values['aud'] ) ? $cswp_apirate_values['aud'] : '';


// Store Switcher Button value.
$convertbtn = CS_Loader::cswp_load_currency_button_data();

$cswp_usd_button = isset( $convertbtn['USD'] ) ? $convertbtn['USD'] : '';

$cswp_inr_button = isset( $convertbtn['INR'] ) ? $convertbtn['INR'] : '';

$cswp_eur_button = isset( $convertbtn['EUR'] ) ? $convertbtn['EUR'] : '';

$cswp_aud_button = isset( $convertbtn['AUD'] ) ? $convertbtn['AUD'] : '';


if ( get_option( 'cswp_display' ) === 'display' ) {
	?>
	<div class="updated notice is-dismissible cswp-notice">
		<p><strong><?php esc_html_e( 'Settings Saved.', 'advanced-currency-switcher' ); ?></strong></p>
	</div>
	<?php
	update_option( 'cswp_display', 'nodisplay' );
}
if ( get_option( 'apivalidate' ) === 'no' ) {
	?>
	<div class="notice notice-error is-dismissible cswp-notice">
	<p><strong><?php esc_html_e( 'The API key you entered seems invalid. Please enter the correct API key & try again.', 'advanced-currency-switcher' ); ?></strong></p>
	</div>
	<?php
	update_option( 'apivalidate', 'ok' );
}
if ( get_option( 'apinotfree' ) === 'notfree' ) {
	?>
	<div class="notice notice-error is-dismissible cswp-notice">
	<p><strong><?php esc_html_e( 'Your API key allows only USD is a base currency. Please change the base currency to USD & save changes again.', 'advanced-currency-switcher' ); ?></strong></p>
	</div>
	<?php
	update_option( 'apinotfree', 'ok' );
}
if ( get_option( 'apinotfree' ) === 'emptyapi' ) {
	?>
	<div class="notice notice-error is-dismissible cswp-notice">
	<p><strong><?php esc_html_e( 'Please enter the API key for get currency rate.', 'advanced-currency-switcher' ); ?></strong></p>
	</div>
	<?php
	update_option( 'apinotfree', 'ok' );
}
?>
<!-- Html code for frontend -->
<form method="post" name="cca_settings_form">
	<!--  set the html code for select base currency and select currency type -->
	<table class="form-table" >
		<tr>
			<th scope="row">
				<label><?php esc_html_e( 'Select Conversion Method', 'advanced-currency-switcher' ); ?></label>
			</th>
			<td>
				<select name="cswp_form_select" id="cswp_currency_form" onchange="showcurency(this)">
					<option id="manual-currency" value="manualrate" <?php selected( $cswp_form_select_value, 'manualrate' ); ?>><?php esc_html_e( 'Manual Conversion Rate', 'advanced-currency-switcher' ); ?></option>
					<option id="api-currency" value="apirate" <?php selected( $cswp_form_select_value, 'apirate' ); ?>><?php esc_html_e( 'Open Exchange Rate API', 'advanced-currency-switcher' ); ?></option>
				</select>
			</td>
		</tr>
	</table>

	<table class="form-table" id="cs-api-display">
		<tr>
			<th scope="row">
				<label for="ApiKey"> <?php esc_html_e( 'App ID', 'advanced-currency-switcher' ); ?></label>
			</th>
			<td>
				<input type="text" name="appid" class="cs-input-appid regular-text" id="cswp-apitext" value="<?php echo esc_attr( $cswp_api_key ); ?>">
				<input type="button" name="Authenticate" value="Authenticate" class="cs-authenticate bt button button-secondary">
				<p class="description cswp_apidescription">
					<?php esc_html_e( 'Enter your Open Exchange Rate App ID. If you don’t have an App ID, you can refer to the ', 'advanced-currency-switcher' ); ?>
						<a href="https://docs.openexchangerates.org/docs/authentication#register-for-an-app-id" target="_blank">article here</a>
					<?php esc_html_e( 'and get one.', 'advanced-currency-switcher' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="UpdateExchangeRate"><?php esc_html_e( 'Frequency', 'advanced-currency-switcher' ); ?></label>
			</th>
			<td>
				<select name="frequency_reload">
					<option value="manual" <?php selected( $cswp_frequency_reload, 'manual' ); ?>>
						<?php esc_html_e( 'Manual', 'advanced-currency-switcher' ); ?>		
					</option>
					<option value="hourly" <?php selected( $cswp_frequency_reload, 'hourly' ); ?>>
						<?php esc_html_e( 'Hourly', 'advanced-currency-switcher' ); ?>
					</option>
					<option value="daily" <?php selected( $cswp_frequency_reload, 'daily' ); ?>>
						<?php esc_html_e( 'Daily', 'advanced-currency-switcher' ); ?>							
					</option>
					<option value="weekly" <?php selected( $cswp_frequency_reload, 'weekly' ); ?>>
						<?php esc_html_e( 'Weekly', 'advanced-currency-switcher' ); ?>							
					</option>
				</select>
				<p class="description cswp_apidescription">
					<?php esc_html_e( 'Set how frequently you want to update the currency conversion rate. This setting is helpful to reduce API calls.', 'advanced-currency-switcher' ); ?>
				</p>
			</td>
		</tr>
	</table>

	<!--  set the html code for put manual currancy rate -->
	<table class="form-table" id="cs-manual-display" >
		<tr>
			<th class="cca-column" >Base Currency</th>
			<th class="cca-column" style="padding-left: 10px;"><?php esc_html_e( 'Currency', 'advanced-currency-switcher' ); ?></th>
			<th class="cca-column" style="padding-left: 10px;"><?php esc_html_e( 'Conversion Rate', 'advanced-currency-switcher' ); ?></th>
			<th class="cca-column ccatable" style="padding-left: 10px;"><?php esc_html_e( 'Display at Frontend?', 'advanced-currency-switcher' ); ?></th>
			<th class="cca-column" style="padding-left: 10px;"><?php esc_html_e( 'Button / Dropdown Label', 'advanced-currency-switcher' ); ?></th>
			<th class="cca-column" style="padding-left: 10px;"><?php esc_html_e( 'Currency Symbol', 'advanced-currency-switcher' ); ?></th>
		</tr>

		<tr>
			<td>
				<label class="currency-switcher-switch">
				<?php
				if ( isset( $cswp_basecurency ) ) {
					if ( 'USD' === $cswp_basecurency ) {
						?>
						<input type="radio"  value="USD" name="basecurency" class="cca_hidden" checked="checked" />
						<?php
					} else {
						?>
						<input type="radio"  value="USD" name="basecurency" class="cca_hidden" checked="checked"/>
						<?php
					}
				} else {
					?>
					<input type="radio"  value="USD" name="basecurency" class="cca_hidden"  />
					<?php
				}
				?>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<label for="USD"><?php esc_html_e( 'United States Dollar (USD)', 'advanced-currency-switcher' ); ?>
				</label>
			</td>
			<td>
				<input step="any" class="cswp_manual_field" type="number" name="usd"  value="<?php echo esc_attr( $cswp_usd_rate ); ?>" placeholder="<?php esc_html_e( 'Enter the USD value', 'advanced-currency-switcher' ); ?>" >
				<input step="any" class="cswp_api_field" type="number" name="usdd"  value="<?php echo esc_attr( $apitext_usd ); ?>" placeholder="<?php esc_html_e( 'Enter the USD value', 'advanced-currency-switcher' ); ?>" readonly>
			</td>
			<td class="ccatable">
				<label class="currency-switcher-switch">
				<?php
				$convertbtn = CS_Loader::cswp_load_currency_button_data();
				?>
					<label for="usdCurrencyButton">
						<input type="checkbox" id="usdCurrencyButton" name="currency_button[]" value="USD" <?php checked( $cswp_usd_button, 'USD' ); ?>>
					</label>
					<br>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<input type="text" name="usd-text"  value="<?php echo esc_attr( $cswp_usd_text ); ?>" placeholder="<?php esc_html_e( 'Enter Button Text', 'advanced-currency-switcher' ); ?>" >
			</td>
			<td>
				<input type="text" name="usd-symbol"  value="<?php echo esc_attr( $cswp_usd_symbol ); ?>" placeholder="<?php esc_html_e( 'Provide Symbol ', 'advanced-currency-switcher' ); ?>" >
			</td>
		</tr>
		<tr>
			<td>
				<label class="currency-switcher-switch">
					<input type="radio"  value="EUR" name="basecurency" class="cca_hidden" <?php checked( $cswp_basecurency, 'EUR' ); ?>>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<label for="EUR"><?php esc_html_e( 'European Union (EUR)', 'advanced-currency-switcher' ); ?>
				</label>
			</td>

			<td>
				<input step="any" class="cswp_manual_field" type="number" name="eur"  value="<?php echo esc_attr( $cswp_eur_rate ); ?>" placeholder="<?php esc_html_e( 'Enter the EURO value', 'advanced-currency-switcher' ); ?>">
				<input step="any" class="cswp_api_field" type="number" name="eurr"  value="<?php echo esc_attr( $apitext_eur ); ?>" placeholder="<?php esc_html_e( 'Enter the EURO value', 'advanced-currency-switcher' ); ?>" readonly>
			</td>
			<td class="ccatable">
				<label class="currency-switcher-switch">
					<label for="eurCurrencyButton">
						<input type="checkbox" id="eurCurrencyButton" name="currency_button[]" value="EUR" <?php checked( $cswp_eur_button, 'EUR' ); ?>>
					</label>
					<br>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<input type="text" name="eur-text"  value="<?php echo esc_attr( $cswp_eur_text ); ?>" placeholder="<?php esc_html_e( 'Enter Button Text', 'advanced-currency-switcher' ); ?>" >
			</td>
			<td>
				<input type="text" name="eur-symbol"  value="<?php echo esc_attr( $cswp_eur_symbol ); ?>" placeholder="<?php esc_html_e( 'Provide Symbol ', 'advanced-currency-switcher' ); ?>" >
			</td>
		</tr>
		<tr>
			<td>
				<label class="currency-switcher-switch">
					<input type="radio"  value="AUD" name="basecurency" class="cca_hidden" <?php checked( $cswp_basecurency, 'AUD' ); ?>>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<label for="AUD"><?php esc_html_e( 'Australian Dollar (AUD)', 'advanced-currency-switcher' ); ?>
			</td>
			<td>
				<input step="any" class="cswp_manual_field" type="number" name="aud"  value="<?php echo esc_attr( $cswp_aud_rate ); ?>" placeholder="<?php esc_html_e( 'Enter the AUD value', 'advanced-currency-switcher' ); ?>">
				<input step="any" class="cswp_api_field" type="number" name="audd"  value="<?php echo esc_attr( $apitext_aud ); ?>" placeholder="<?php esc_html_e( 'Enter the AUD value', 'advanced-currency-switcher' ); ?>" readonly>
			</td>
			<td class="ccatable">
				<label class="currency-switcher-switch">
					<label for="audCurrencyButton">
						<input type="checkbox" id="audCurrencyButton" name="currency_button[]" value="AUD" <?php checked( $cswp_aud_button, 'AUD' ); ?>>
					</label>
					<br>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<input type="text" name="aud-text"  value="<?php echo esc_attr( $cswp_aud_text ); ?>" placeholder="<?php esc_html_e( 'Enter Button Text', 'advanced-currency-switcher' ); ?>" >
			</td>
			<td>
				<input type="text" name="aud-symbol"  value="<?php echo esc_attr( $cswp_aud_symbol ); ?>" placeholder="<?php esc_html_e( 'Provide Symbol ', 'advanced-currency-switcher' ); ?>" >
			</td>
		</tr>
		<tr>
			<td>
				<label class="currency-switcher-switch">
					<input type="radio"  value="INR" name="basecurency" class="cca_hidden" <?php checked( $cswp_basecurency, 'INR' ); ?>>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<label for="INR"><?php esc_html_e( 'Indian Rupee (INR)', 'advanced-currency-switcher' ); ?>
				</label>
			</td>			
			<td>
				<input step="any" class="cswp_manual_field" type="number" name="inr" value="<?php echo esc_attr( $cswp_inr_rate ); ?>" placeholder="<?php esc_html_e( 'Enter the INR value', 'advanced-currency-switcher' ); ?>">			
				<input step="any" class="cswp_api_field" type="number" name="inrr"  value="<?php echo esc_attr( $apitext_inr ); ?>" placeholder="<?php esc_html_e( 'Enter the INR value', 'advanced-currency-switcher' ); ?>" readonly>
			</td>
			<td class="ccatable">
				<label class="currency-switcher-switch">
					<label for="inrCurrencyButton">
						<input type="checkbox" id="inrCurrencyButton" name="currency_button[]" value="INR" <?php checked( $cswp_inr_button, 'INR' ); ?>>
					</label>
					<br>
					<span class="currency-switcher-slider round">
					</span>
				</label>
			</td>
			<td>
				<input type="text" name="inr-text"  value="<?php echo esc_attr( $cswp_inr_text ); ?>" placeholder="<?php esc_html_e( 'Enter Button Text', 'advanced-currency-switcher' ); ?>" >
			</td>
			<td>
				<input type="text" name="inr-symbol"  value="<?php echo esc_attr( $cswp_inr_symbol ); ?>" placeholder="<?php esc_html_e( 'Provide Symbol ', 'advanced-currency-switcher' ); ?>" >
			</td>
		</tr>
		<tr>
			<td colspan="6" class="cswp_note_rate">
			<p class="description cswp_manual_field">
				<b><?php esc_html_e( 'Note:', 'advanced-currency-switcher' ); ?></b>	<?php esc_html_e( ' Please make sure you enter 1 as the Conversion rate for the selected base currency and enter an exchange rate for the other currencies in the list.', 'advanced-currency-switcher' ); ?><br>
				<?php esc_html_e( 'Please select at least one checkbox except the base currency in the', 'advanced-currency-switcher' ); ?><b><?php esc_html_e( ' Display on the Frontend column.', 'advanced-currency-switcher' ); ?></b>
				</p>
			</td>
		</tr>
	</table>

	<!--  set the html code for Apikey value and frequency update time -->
	<table class="form-table">
		<tr>
			<th>Display Type</th>
			<td>
				<select name="cswp_button_type" >
						<option value="dropdown" <?php selected( $cswp_button_type_value, 'dropdown' ); ?>>Drop Down</option>
						<option value="toggle" <?php selected( $cswp_button_type_value, 'toggle' ); ?>>Toggle</option>
						<option value="button" <?php selected( $cswp_button_type_value, 'button' ); ?>>Button</option>
				</select>
				<p class="description cswp_apidescription">
					<?php esc_html_e( 'Select how you wish to display the currency conversion action at frontend.', 'advanced-currency-switcher' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="DecimalPlaces"><?php esc_html_e( 'Number Format', 'advanced-currency-switcher' ); ?></label>
			</th>
			<td>
				<select name="cswp_decimal_place_value" >
					<option value="0" <?php selected( $cswp_decimal_place_value, null ); ?>>
						<?php esc_html_e( 'Round Number (e.g.12)', 'advanced-currency-switcher' ); ?>
					</option>
					<option value="1" <?php selected( $cswp_decimal_place_value, 1 ); ?>>
						<?php esc_html_e( '1 Decimal Place (e.g.12.3)', 'advanced-currency-switcher' ); ?>
					</option>
					<option value="2" <?php selected( $cswp_decimal_place_value, 2 ); ?>>
						<?php esc_html_e( '2 Decimal Places (e.g.12.34)', 'advanced-currency-switcher' ); ?>
					</option>
				</select>
				<p class="description cswp_apidescription">
					<?php esc_html_e( 'Control decimal places of the currency that displays after conversion.', 'advanced-currency-switcher' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th>
				<?php
					wp_nonce_field( 'cs-form-nonce', 'cs-form' );
				?>
				<input type="submit" name="submit" value="<?php esc_html_e( 'Save Changes', 'advanced-currency-switcher' ); ?>" class="bt button button-primary">
			</th>
		</tr>
	</table>
</form>
<?php
