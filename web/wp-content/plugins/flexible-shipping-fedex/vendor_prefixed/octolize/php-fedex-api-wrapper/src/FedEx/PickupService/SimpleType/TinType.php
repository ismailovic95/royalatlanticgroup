<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TinType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class TinType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BUSINESS_NATIONAL = 'BUSINESS_NATIONAL';
    const _BUSINESS_STATE = 'BUSINESS_STATE';
    const _BUSINESS_UNION = 'BUSINESS_UNION';
    const _PERSONAL_NATIONAL = 'PERSONAL_NATIONAL';
    const _PERSONAL_STATE = 'PERSONAL_STATE';
}
