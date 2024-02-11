<?php
/**
 * Plugin main class.
 *
 * @package Octolize\Shipping\CanadaPost;
 */

namespace Octolize\Shipping\CanadaPost;

use Octolize\Shipping\CanadaPost\Beacon\Beacon;
use Octolize\Shipping\CanadaPost\Beacon\BeaconDisplayStrategy;
use OctolizeShippingCanadaPostVendor\Octolize\ShippingExtensions\ShippingExtensions;
use OctolizeShippingCanadaPostVendor\Octolize\Tracker\TrackerInitializer;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostShippingService;
use OctolizeShippingCanadaPostVendor\WPDesk\Logger\SimpleLoggerFactory;
use OctolizeShippingCanadaPostVendor\WPDesk\Notice\AjaxHandler;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\DisplayStrategy\ShippingMethodDisplayDecision;
use OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\RatingPetitionNotice;
use OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\RepositoryRatingPetitionText;
use OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\TextPetitionDisplayer;
use OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\TimeWatcher\ShippingMethodGlobalSettingsWatcher;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\AddMethodReminder\AddMethodReminder;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostAdminOrderMetaDataDisplay;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostShippingMethod;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\AdminOrderMetaDataDisplay;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\FrontOrderMetaDataDisplay;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleAdminOrderMetaDataInterpreterImplementation;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\PluginShippingDecisions;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters\FallbackAdminMetaDataInterpreter;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters\PackedPackagesAdminMetaDataInterpreter;
use OctolizeShippingCanadaPostVendor\WPDesk_Plugin_Info;
use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerAwareInterface;
use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerAwareTrait;
use OctolizeShippingCanadaPostVendor\Psr\Log\NullLogger;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @package WPDesk\OctolizeShippingCanadaPost
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Scripts version.
	 *
	 * @var string
	 */
	private $scripts_version = '1';

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		if ( defined( 'OCTOLIZE_SHIPPING_CANADA_POST_VERSION' ) ) {
			$this->scripts_version = OCTOLIZE_SHIPPING_CANADA_POST_VERSION . '.' . $this->scripts_version;
		}
		parent::__construct( $plugin_info );
		$this->setLogger( $this->is_debug_mode() ? ( new SimpleLoggerFactory( 'canadapost' ) )->getLogger() : new NullLogger() );
		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
	}

	/**
	 * Returns true when debug mode is on.
	 *
	 * @return bool
	 */
	private function is_debug_mode() {
		$global_canada_post_settings = $this->get_global_canada_post_settings();

		return isset( $global_canada_post_settings['debug_mode'] ) && 'yes' === $global_canada_post_settings['debug_mode'];
	}


	/**
	 * Get global Canada Post settings.
	 *
	 * @return string[]
	 */
	private function get_global_canada_post_settings() {
		/** @phpstan-ignore-next-line */
		return get_option( 'woocommerce_' . CanadaPostShippingService::UNIQUE_ID . '_settings', [] );
	}

	/**
	 * Init plugin
	 *
	 * @return void
	 */
	public function init() {
		$global_canada_post_settings = new SettingsValuesAsArray( $this->get_global_canada_post_settings() );

		$origin_country = $this->get_origin_country_code( $global_canada_post_settings );

		// @phpstan-ignore-next-line.
		$canada_post_service = apply_filters( 'octolize_shipping_canada_post_shipping_service', new CanadaPostShippingService( $this->logger, new ShopSettings( CanadaPostShippingService::UNIQUE_ID ), $origin_country ) );

		$this->add_hookable(
			new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\Assets( $this->get_plugin_url() . 'vendor_prefixed/wpdesk/wp-woocommerce-shipping/assets', 'canada-post' )
		);
		$this->init_repository_rating();

		$admin_meta_data_interpreter = new AdminOrderMetaDataDisplay( CanadaPostShippingService::UNIQUE_ID );
		$admin_meta_data_interpreter->add_interpreter(
			new SingleAdminOrderMetaDataInterpreterImplementation(
				WooCommerceShippingMetaDataBuilder::SERVICE_TYPE,
				__( 'Service Code', 'octolize-canada-post-shipping' )
			)
		);
		$admin_meta_data_interpreter->add_interpreter( new FallbackAdminMetaDataInterpreter() );
		$admin_meta_data_interpreter->add_hidden_order_item_meta_key( WooCommerceShippingMetaDataBuilder::COLLECTION_POINT );
		$admin_meta_data_interpreter->add_interpreter( new PackedPackagesAdminMetaDataInterpreter() );
		$this->add_hookable( $admin_meta_data_interpreter );

		$meta_data_interpreter = new FrontOrderMetaDataDisplay( CanadaPostShippingService::UNIQUE_ID );
		$this->add_hookable( $meta_data_interpreter );

		/**
		 * Handles API Status AJAX requests.
		 *
		 * @var FieldApiStatusAjax $api_ajax_status_handler .
		 */
		// @phpstan-ignore-next-line.
		$api_ajax_status_handler = new FieldApiStatusAjax( $canada_post_service, $global_canada_post_settings, $this->logger );
		$this->add_hookable( $api_ajax_status_handler );

		// @phpstan-ignore-next-line.
		$plugin_shipping_decisions = new PluginShippingDecisions( $canada_post_service, $this->logger );
		$plugin_shipping_decisions->set_field_api_status_ajax( $api_ajax_status_handler );

		CanadaPostShippingMethod::set_plugin_shipping_decisions( $plugin_shipping_decisions );

		$this->add_hookable( new CanadaPostAdminOrderMetaDataDisplay( CanadaPostShippingService::UNIQUE_ID ) );

		$this->add_hookable(
			new AddMethodReminder(
				$canada_post_service->get_name(),
				$canada_post_service::UNIQUE_ID,
				$canada_post_service::UNIQUE_ID,
				CanadaPostSettingsDefinition::USERNAME
			)
		);

		$this->add_hookable( new ShippingExtensions( $this->plugin_info ) );

		$this->init_tracker();

		parent::init();
	}

	/**
	 * @return void
	 */
	private function init_tracker() {
		$this->add_hookable( TrackerInitializer::create_from_plugin_info_for_shipping_method( $this->plugin_info, CanadaPostShippingService::UNIQUE_ID ) );
	}

	/**
	 * Show repository rating notice when time comes.
	 *
	 * @return void
	 */
	private function init_repository_rating() {
		$this->add_hookable( new AjaxHandler( trailingslashit( $this->get_plugin_url() ) . 'vendor_prefixed/wpdesk/wp-notice/assets' ) );

		$time_tracker = new ShippingMethodGlobalSettingsWatcher( CanadaPostShippingService::UNIQUE_ID );
		$this->add_hookable( $time_tracker );
		$this->add_hookable(
			new RatingPetitionNotice(
				$time_tracker,
				CanadaPostShippingService::UNIQUE_ID,
				$this->plugin_info->get_plugin_name(),
				'https://octol.io/rate-cp'
			)
		);

		$this->add_hookable(
			new TextPetitionDisplayer(
				'woocommerce_after_settings_shipping',
				new ShippingMethodDisplayDecision( new \WC_Shipping_Zones(), CanadaPostShippingService::UNIQUE_ID ),
				new RepositoryRatingPetitionText(
					'Octolize',
					__( 'Canada Post Live Rates for WooCommerce', 'octolize-canada-post-shipping' ),
					'https://octol.io/rate-cp',
					'center'
				)
			)
		);
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();

		add_filter( 'woocommerce_shipping_methods', [ $this, 'woocommerce_shipping_methods_filter' ], 20, 1 );

		add_filter(
			'pre_option_woocommerce_settings_shipping_recommendations_hidden',
			function () {
				return 'yes';
			}
		);

		$this->add_hookable( new SettingsSidebar() );

		$beacon = new Beacon(
			new BeaconDisplayStrategy(),
			trailingslashit( $this->get_plugin_url() ) . 'vendor_prefixed/wpdesk/wp-helpscout-beacon/assets/'
		);
		$beacon->hooks();

		$this->hooks_on_hookable_objects();
	}

	/**
	 * Adds shipping method to Woocommerce.
	 *
	 * @param string[] $methods Methods.
	 *
	 * @return string[]
	 */
	public function woocommerce_shipping_methods_filter( $methods ) {
		$methods[ CanadaPostShippingService::UNIQUE_ID ] = CanadaPostShippingMethod::class;

		return $methods;
	}

	/**
	 * Quick links on plugins page.
	 *
	 * @param string[] $links .
	 *
	 * @return string[]
	 */
	public function links_filter( $links ) {
		$docs_link    = 'https://octol.io/cp-docs';
		$support_link = 'https://octol.io/cp-support';
		$settings_url = \admin_url( 'admin.php?page=wc-settings&tab=shipping&section=' . CanadaPostShippingService::UNIQUE_ID );

		$external_attributes = ' target="_blank" ';

		$plugin_links = [
			'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'octolize-canada-post-shipping' ) . '</a>',
			'<a href="' . esc_url( $docs_link ) . '"' . $external_attributes . '>' . __( 'Docs', 'octolize-canada-post-shipping' ) . '</a>',
			'<a href="' . esc_url( $support_link ) . '"' . $external_attributes . '>' . __( 'Support', 'octolize-canada-post-shipping' ) . '</a>',
		];

		if ( ! defined( 'OCTOLIZE_CANADA_POST_SHIPPING_PRO_VERSION' ) ) {
			$upgrade_link   = 'https://octol.io/cp-upgrade';
			$plugin_links[] = '<a target="_blank" href="' . $upgrade_link . '" style="color:#d64e07;font-weight:bold;">' . __( 'Upgrade', 'octolize-canada-post-shipping' ) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Get origin country code.
	 *
	 * @param SettingsValuesAsArray $global_canada_post_settings .
	 *
	 * @return string
	 */
	private function get_origin_country_code( $global_canada_post_settings ) {
		if ( 'yes' === $global_canada_post_settings->get_value( CanadaPostSettingsDefinition::CUSTOM_ORIGIN, 'no' ) ) {
			$origin_country_code_with_state = $global_canada_post_settings->get_value( CanadaPostSettingsDefinition::ORIGIN_COUNTRY, '' );
		} else {
			$origin_country_code_with_state = get_option( 'woocommerce_default_country', '' );
		}

		/** @phpstan-ignore-next-line */
		[ $origin_country ] = explode( ':', $origin_country_code_with_state );

		return $origin_country;
	}

}
