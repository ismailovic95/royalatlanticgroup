<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * These values are used to control the availability of certain special services at the time when a customer uses the e-mail label link to create a return shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class ReturnEMailAllowedSpecialServiceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _SATURDAY_DELIVERY = 'SATURDAY_DELIVERY';
    const _SATURDAY_PICKUP = 'SATURDAY_PICKUP';
}
