<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
/**
 * Can build Canad Post meta data.
 */
class CanadaPostMetaDataBuilder extends \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder
{
    const META_CANADA_POST_SERVICE_CODE = 'canada_post_service_id';
    /**
     * Build meta data for rate.
     *
     * @param SingleRate $rate .
     * @param Shipment $shipment .
     *
     * @return array
     */
    public function build_meta_data_for_rate(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $meta_data = parent::build_meta_data_for_rate($rate, $shipment);
        $meta_data = $this->add_canada_post_service_code_to_metadata($meta_data, $rate);
        return $meta_data;
    }
    /**
     * Add Canada Post service to metadata.
     *
     * @param array $meta_data
     * @param SingleRate $rate
     *
     * @return array
     */
    private function add_canada_post_service_code_to_metadata(array $meta_data, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate)
    {
        $meta_data[self::META_CANADA_POST_SERVICE_CODE] = $rate->service_type;
        return $meta_data;
    }
}
