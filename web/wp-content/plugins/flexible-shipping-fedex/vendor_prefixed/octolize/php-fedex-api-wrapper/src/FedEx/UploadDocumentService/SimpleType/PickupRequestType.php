<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PickupRequestType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class PickupRequestType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FUTURE_DAY = 'FUTURE_DAY';
    const _SAME_DAY = 'SAME_DAY';
}
