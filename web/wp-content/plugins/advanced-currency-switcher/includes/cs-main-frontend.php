<?php
/**
 * Add tabs
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

?>

<div class="pr_main_heading">

	<h1> <?php esc_html_e( 'Currency Switcher', 'advanced-currency-switcher' ); ?> </h1>

</div>

<?php

// To get the tab value from URL and store in $active_tab variable.
$active_tab = 'cs_settings';
if ( isset( $_GET['tab'] ) ) {

	if ( 'cs_settings' === $_GET['tab'] ) {

		$active_tab = 'cs_settings';

	} elseif ( 'user-manual' === $_GET['tab'] ) {

		$active_tab = 'user-manual';

	}
}

?>

<h2 class="nav-tab-wrapper">

	<a href="?page=currency_switch" class="nav-tab tb
	<?php
	if ( 'cs_settings' === $active_tab ) {
		echo 'nav-tab-active';
	}
	?>
	">
		<?php esc_html_e( 'Global Settings', 'advanced-currency-switcher' ); ?>
	</a>

	<a href="?page=currency_switch&tab=user-manual" class="nav-tab tb
	<?php
	if ( 'user-manual' === $active_tab ) {
		echo 'nav-tab-active';
	}
	?>
	">
		<?php esc_html_e( 'User Manual', 'advanced-currency-switcher' ); ?>
	</a>

</h2>

<?php

// here we display the sections and options in the settings page based on the active tab.
if ( isset( $_GET['tab'] ) ) {

	if ( 'cs_settings' === $_GET['tab'] ) {

		require_once 'cs-settings-frontend.php';

	} elseif ( 'user-manual' === $_GET['tab'] ) {

		require_once 'cs-user-manual.php';
	}
} else {

	require_once 'cs-settings-frontend.php';
}
