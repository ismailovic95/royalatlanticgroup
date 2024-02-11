<?php

/**
 * Capability: CollectionPointsProvider class
 *
 * @package WPDesk\AbstractShipping\CollectionPointCapability
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\CollectionPointCapability;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Address;
/**
 * Interface for classes that provides collections points.
 */
interface CollectionPointsProvider
{
    /**
     * Get nearest collection points to given address.
     *
     * @param Address $address .
     *
     * @return CollectionPoint[]
     * @throws CollectionPointNotFoundException
     */
    public function get_nearest_collection_points(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Address $address);
    /**
     * Get single nearest collection point to given address.
     *
     * @param Address $address .
     *
     * @return CollectionPoint
     * @throws CollectionPointNotFoundException
     */
    public function get_single_nearest_collection_point(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Address $address);
    /**
     * Get get collection point by given id.
     *
     * @param string $collection_point_id .
     * @param string $country_code .
     *
     * @return CollectionPoint
     * @throws CollectionPointNotFoundException .
     */
    public function get_point_by_id($collection_point_id, $country_code);
}
