<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\AddMethodReminder;

use OctolizeShippingCanadaPostVendor\WPDesk\Notice\Notice;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use OctolizeShippingCanadaPostVendor\WPDesk\ShowDecision\GetStrategy;
/**
 * Can display and track missing shipping method reminder.
 */
class AddMethodReminder implements \OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    private $method_title;
    /**
     * @var string
     */
    private $method_id;
    /**
     * @var string
     */
    private $tracker_name;
    /**
     * @var string
     */
    private $settings_field_to_check;
    /**
     * @param string $method_title
     * @param string $method_id
     * @param string $tracker_name
     * @param string $settings_field_to_check
     */
    public function __construct(string $method_title, string $method_id, string $tracker_name, string $settings_field_to_check)
    {
        $this->method_title = $method_title;
        $this->method_id = $method_id;
        $this->tracker_name = $tracker_name;
        $this->settings_field_to_check = $settings_field_to_check;
    }
    public function hooks()
    {
        \add_action('admin_notices', [$this, 'display_notice_if_should']);
        (new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\AddMethodReminder\ClickNoticeTracker($this->method_id))->hooks();
        (new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\AddMethodReminder\DeactivationTrackerData($this->method_id))->hooks();
        (new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\AddMethodReminder\TrackerData($this->method_id, $this->tracker_name))->hooks();
    }
    public function display_notice_if_should()
    {
        $display_strategy = new \OctolizeShippingCanadaPostVendor\WPDesk\ShowDecision\GetStrategy([['page' => 'wc-settings', 'tab' => 'shipping', 'section' => $this->method_id]]);
        if ($display_strategy->shouldDisplay() && $this->shipping_method_settings_saved() && !$this->method_added_in_any_zone($this->method_id)) {
            $notice = new \OctolizeShippingCanadaPostVendor\WPDesk\Notice\Notice(\sprintf(\__('%1$sPlease add now the %2$s shipping method within the shipping zone you want it to be available in →%3$s', 'octolize-canada-post-shipping'), '<a href="' . \admin_url('admin.php?page=wc-settings&tab=shipping&track_click_notice_method=' . \wp_create_nonce($this->method_id)) . '">', $this->method_title, '</a>'), \OctolizeShippingCanadaPostVendor\WPDesk\Notice\Notice::NOTICE_TYPE_WARNING);
        }
    }
    private function shipping_method_settings_saved()
    {
        $settings = \get_option('woocommerce_' . $this->method_id . '_settings', []);
        return \is_array($settings) && !empty($settings[$this->settings_field_to_check]);
    }
    /**
     * @param $method_id
     *
     * @return bool
     */
    private function method_added_in_any_zone($method_id)
    {
        $zones = \WC_Shipping_Zones::get_zones();
        $zones[0] = new \WC_Shipping_Zone(0);
        foreach ($zones as $zone_id => $zone) {
            $zone = new \WC_Shipping_Zone($zone_id);
            $shipping_methods = $zone->get_shipping_methods(\true);
            foreach ($shipping_methods as $shipping_method) {
                if ($shipping_method instanceof \WC_Shipping_Method && $shipping_method->id === $method_id) {
                    return \true;
                }
            }
        }
        return \false;
    }
}
