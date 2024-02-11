<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
