<?php

/**
 * @package WPDesk\WooCommerceShipping\CanadaPost
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\AdminOrderMetaDataDisplay;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleAdminOrderMetaDataInterpreterImplementation;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
/**
 * Can hide meta data in order.
 */
class CanadaPostAdminOrderMetaDataDisplay extends \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\AdminOrderMetaDataDisplay
{
    /**
     * @param string $method_id .
     */
    public function __construct($method_id)
    {
        parent::__construct($method_id);
        $this->add_hidden_order_item_meta_key(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder::SERVICE_TYPE);
        $this->add_interpreter(new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleAdminOrderMetaDataInterpreterImplementation(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostMetaDataBuilder::META_CANADA_POST_SERVICE_CODE, \__('Canada Post Service Code', 'octolize-canada-post-shipping')));
    }
}
