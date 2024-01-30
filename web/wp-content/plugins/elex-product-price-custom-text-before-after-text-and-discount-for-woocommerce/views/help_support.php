<div class="elex-ppct-wrap">
	<!-- content -->
	<div class="elex-ppct-content d-flex">
		<!-- main content -->
		<div class="elex-ppct-main">
			<div class="p-2 pe-4">
			<img src="<?php echo esc_url( plugins_url( 'assets/images/top banner.png', dirname( __FILE__ ) ) ); ?>" alt="" class="w-100">
				<?php require __DIR__ . '/help_support_header.php'; ?>
				<?php
				/**
				 * This is a action hook which is responsible for taggling the tabs in help and support submenu
				 *
				 * @since 1.0.0
				 */
				 do_action( 'ppct_settings_tab_' . $active_tab );
				?>
			</div>
		</div>
	</div>
</div>
