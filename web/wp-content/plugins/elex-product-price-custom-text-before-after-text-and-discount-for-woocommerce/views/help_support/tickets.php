<?php
use ELEX\PPCT\HelpAndSupport\HelpAndSupportController;
?>
<div class="eacmcm p-3">
	<div class="p-1 fw-bold">
		<p><?php esc_html_e( 'Before raising the ticket, we recommend you to go through our detailed', 'elex-product-price-custom-text-and-discount' ); ?> <a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin/" target="_blank"><?php esc_html_e( 'documentation.', 'elex-product-price-custom-text-and-discount' ); ?></a></p>
		<p><?php esc_html_e( 'Or', 'elex-product-price-custom-text-and-discount' ); ?></p>
		<p class="mb-0"><?php esc_html_e( 'To get in touch with one of our helpdesk representatives, please raise a support ticket on our website.', 'elex-product-price-custom-text-and-discount' ); ?></p>
		<div class="text-danger fw-normal"><small><?php esc_html_e( '* Please don`t forget to attach your System Info File with the request for better support.', 'elex-product-price-custom-text-and-discount' ); ?></small></div>

		<!-- <button class="btn btn-primary py-3 my-3">Raise a Ticket</button> -->

		<a href='https://support.elextensions.com/' target="_blank"><button type="button" class="btn btn-primary py-2 my-3" id="elex_support"><?php echo esc_html_e( 'Raise a ticket', 'elex-product-price-custom-text-and-discount' ); ?></button></a>
		<div class="d-flex gap-3">

			<form action="<?php echo esc_url( admin_url( 'admin.php?page=ppct-help_support&tab=ticket' ) ); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'action', 'system_info_nonce' ); ?>
				<input type="hidden" name="action" value="raq_download_system_info" />

				<div>
					<textarea hidden readonly="readonly" onclick="this.focus();this.select()" id="ssi-textarea" name="send-system-info-textarea-raq" title="<?php esc_html_e( 'To copy the System Info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'elex-product-price-custom-text-and-discount' ); ?>">
							<?php
							// $system_info = new Request_a_Quote();
							// echo esc_html($system_info->display());
							echo esc_html( HelpAndSupportController::display() );
							?>
							</textarea>
				</div>

				<p class="submit">
					<input type="submit" class="btn btn-outline-primary" value="<?php esc_html_e( 'Download System Info', 'elex-product-price-custom-text-and-discount' ); ?>" />
				</p>
			</form>

		</div>

	</div>

</div>
