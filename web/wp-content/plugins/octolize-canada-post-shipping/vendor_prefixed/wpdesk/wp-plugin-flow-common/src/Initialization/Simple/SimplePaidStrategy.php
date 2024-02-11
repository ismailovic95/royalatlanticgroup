<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\Simple;

use OctolizeShippingCanadaPostVendor\WPDesk\Helper\HelperRemover;
use OctolizeShippingCanadaPostVendor\WPDesk\Helper\PrefixedHelperAsLibrary;
use OctolizeShippingCanadaPostVendor\WPDesk\License\PluginRegistrator;
use OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\ActivationTrait;
use OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\BuilderTrait;
use OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\PluginDisablerByFileTrait;
use OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\InitializationStrategy;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\ActivationAware;
use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\SlimPlugin;
/**
 * Initialize standard paid plugin
 * - register to helper
 * - initialize helper
 * - build with info about plugin active flag
 */
class SimplePaidStrategy implements \OctolizeShippingCanadaPostVendor\WPDesk\Plugin\Flow\Initialization\InitializationStrategy
{
    use TrackerInstanceAsFilterTrait;
    use BuilderTrait;
    /** @var \WPDesk_Plugin_Info */
    private $plugin_info;
    /** @var SlimPlugin */
    private $plugin;
    public function __construct(\OctolizeShippingCanadaPostVendor\WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * Run tasks that prepares plugin to work. Have to run before plugin loaded.
     *
     * @param \WPDesk_Plugin_Info $plugin_info
     *
     * @return SlimPlugin
     */
    public function run_before_init(\OctolizeShippingCanadaPostVendor\WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin = $this->build_plugin($plugin_info);
        $this->init_register_hooks($plugin_info, $this->plugin);
    }
    /**
     * Run task that integrates plugin with other dependencies. Can be run in plugins_loaded.
     *
     * @param \WPDesk_Plugin_Info $plugin_info
     *
     * @return SlimPlugin
     */
    public function run_init(\OctolizeShippingCanadaPostVendor\WPDesk_Plugin_Info $plugin_info)
    {
        if (!$this->plugin) {
            $this->plugin = $this->build_plugin($plugin_info);
        }
        $this->prepare_tracker_action();
        $registrator = $this->register_plugin();
        \add_action('plugins_loaded', function () use($registrator) {
            $is_plugin_subscription_active = $registrator instanceof \OctolizeShippingCanadaPostVendor\WPDesk\License\PluginRegistrator && $registrator->is_active();
            if ($this->plugin instanceof \OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\ActivationAware && $is_plugin_subscription_active) {
                $this->plugin->set_active();
            }
            $this->store_plugin($this->plugin);
            $this->init_plugin($this->plugin);
        }, $priority_before_flow_2_5_after_2_6 = -45);
        return $this->plugin;
    }
    /**
     * Register plugin for subscriptions and updates
     *
     * @return PluginRegistrator
     *
     */
    private function register_plugin()
    {
        if (\apply_filters('wpdesk_can_register_plugin', \true, $this->plugin_info)) {
            $registrator = new \OctolizeShippingCanadaPostVendor\WPDesk\License\PluginRegistrator($this->plugin_info);
            $registrator->initialize_license_manager();
            return $registrator;
        }
    }
}
