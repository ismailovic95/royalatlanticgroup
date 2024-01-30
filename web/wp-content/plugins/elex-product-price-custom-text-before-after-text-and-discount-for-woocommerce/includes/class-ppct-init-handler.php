<?php

namespace ELEX\PPCT ;

use ELEX\PPCT\SettingsController;
use ELEX\PPCT\HelpAndSupport\HelpAndSupportController;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ELEX_PPCT_Init_Handler {


	const VERSION = '1.0.0';
	public $plugin_basename;

	public function with_basename( $basename ) {
		$this->plugin_basename = $basename;

		return $this;
	}

	public function boot() {

		$this->register_hooks();
	}
	public function register_hooks() {
		add_action( 'admin_menu', array( $this, 'eh_crm_menu_add' ), 1 );
		add_action( 'init', array( $this, 'enqueue_scripts' ) );
		$this->register_routes();

		
	}

	public function register_routes() {
		SettingsController::init();
		HelpAndSupportController::init();
		
	}

	public function form_settings_localize_script() {
		
		wp_localize_script(
			'elex_ppct_formsetting',
			'raq_formsetting_ajax_object',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'raq-formsetting-ajax-nonce' ),
			)
		);
	}

	public function enqueue_scripts() {
		global $plugin_page;
		$page         = ( ! empty( filter_input( INPUT_GET, 'page' ) ) ? sanitize_text_field( filter_input( INPUT_GET, 'page' ) ) : '' );
		$include_page = array( 'elex_product_price_custom_text_and_discount', 'ppct-help_support', 'ppct-go-premium' );
		if ( in_array( $page, $include_page ) ) {

			wp_enqueue_script( 'elex_ppct_formsetting', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/form_settings.min.js' ), array( 'jquery', 'wp-element', 'wp-i18n' ), self::VERSION );
			self::form_settings_localize_script();

			wp_enqueue_script( 'elex_ppct_select_2_js', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/select2-min.js' ), array( 'jquery', 'underscore' ), self::VERSION, true );
			wp_enqueue_style( 'elex_ppct_select_2_css', plugins_url( dirname( $this->plugin_basename ) . '/assets/css/select-2-min.css' ), array(), self::VERSION );
			wp_enqueue_script( 'elex_ppct_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/req_script.js' ), array( 'jquery' ), self::VERSION, true );
			
			wp_enqueue_script( 'elex_ppct_popper_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/popper.js' ), array(), self::VERSION, true );
			wp_enqueue_script( 'elex_ppct_bootstrap_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/bootstrap.js' ), array(), self::VERSION, true );
			wp_enqueue_script( 'elex_ppct_fontawesome', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/fontawesome.js' ), array(), self::VERSION, true );
			wp_enqueue_script( 'elex_ppct_chosen', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/settings.js' ), array(), self::VERSION, true );


			wp_enqueue_style( 'elex_ppct_front_style', plugins_url( dirname( $this->plugin_basename ) . '/assets/css/app.css' ), array(), self::VERSION );  
			self::localize_script();
		}
	}

	public function localize_script() {
		wp_localize_script(
			'elex_ppct_script',
			'elex_ppct_ajax_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ppct-ajax-nonce' ),
			)
		);
	}

	public function eh_crm_menu_add() {
		$parent_slug = 'elex_product_price_custom_text_and_discount';

		add_menu_page(
			'Custom Text & Discount',
			'Custom Text & Discount',
			'manage_options',
			$parent_slug,
			array( SettingsController::class, 'load_view' ),
			esc_url( plugins_url( dirname( $this->plugin_basename ) . '/assets/images/ELEX-grey-logo-forsidebar.svg' ) ),
			57
		);

		add_submenu_page(
			$parent_slug,
			'Settings',
			'Settings',
			'manage_options',
			$parent_slug, 
			array( SettingsController::class, 'load_view' )
		);

		add_submenu_page(
			$parent_slug,
			'Help & Support',
			'Help & Support',
			'manage_options',
			'ppct-help_support',
			array( HelpAndSupportController::class, 'load_view' )
		);

		add_submenu_page(
			$parent_slug,
			'Go Premium!',
			__( "<span style='color: #008000;' class='text-success'>Go Premium!</span>" ),
			'manage_options',
			'ppct-go-premium',
			array( $this, 'ppct_Licence_callback' )
		);

	}

	public function ppct_Licence_callback() {
		?>
				<div class="elex-ppct-wrap">
			<!-- content -->
			<div class="elex-ppct-content d-flex">
				<!-- main content -->
				<div class="elex-ppct-main">
					<div class="p-2 pe-4">
					<img src="<?php echo esc_url( plugins_url( 'assets/images/top banner.png', dirname( __FILE__ ) ) ); ?>" alt="" class="w-100">
		<?php 
			  $plugin_name = 'elexCustomTextAndDiscount';
					include WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __DIR__ ) ) . '/includes/market.php';
		?>
					</div>
				</div>
			</div>
		</div>
		<?php

	}

}
