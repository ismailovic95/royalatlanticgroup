<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
