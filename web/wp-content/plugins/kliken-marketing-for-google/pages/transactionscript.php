<?php
/**
 * Contains JS script to register a transaction up on order received page.
 *
 * @package Kliken Marketing for Google
 */

defined( 'ABSPATH' ) || exit;

?>

<script type="text/javascript">
	var swPreRegister = function() {
		sw.gawCurrency = "<?php echo esc_js( $trans['currency'] ); ?>";

		var trans = sw.create_transaction(
			"<?php echo esc_js( $trans['order_id'] ); ?>",
			"<?php echo esc_js( $trans['affiliate'] ); ?>",
			"<?php echo esc_js( $trans['sub_total'] ); ?>",
			"<?php echo esc_js( $trans['tax'] ); ?>",
			"<?php echo esc_js( $trans['city'] ); ?>",
			"<?php echo esc_js( $trans['state'] ); ?>",
			"<?php echo esc_js( $trans['country'] ); ?>",
			"<?php echo esc_js( $trans['total'] ); ?>",
		);

		<?php foreach ( $trans['items'] as $index => $item ) : ?>
			trans.add_item(
				"<?php echo esc_js( $item['id'] ); ?>",
				"<?php echo esc_js( $item['name'] ); ?>",
				"<?php echo esc_js( $item['category'] ); ?>",
				"<?php echo esc_js( $item['price'] ); ?>",
				"<?php echo esc_js( $item['quantity'] ); ?>",
			);
		<?php endforeach; ?>

		sw.hit.set_page("ORDER_CONFIRMATION");
	};
</script>
