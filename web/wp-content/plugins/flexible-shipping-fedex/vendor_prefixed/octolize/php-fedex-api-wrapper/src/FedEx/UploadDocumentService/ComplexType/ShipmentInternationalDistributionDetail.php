<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies the attributes of a shipment related to its role in an international distribution (consolidation).
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property string $ClearanceFacilityLocId
 * @property \FedEx\UploadDocumentService\SimpleType\DistributionClearanceType|string $ClearanceType
 * @property InternationalDistributionSummaryDetail $SummaryDetail
 * @property SplitPaymentSpecification $SplitPaymentSpecification
 */
class ShipmentInternationalDistributionDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ShipmentInternationalDistributionDetail';
    /**
     * Identifies the FedEx facility at which customs clearance will be performed.
     *
     * @param string $clearanceFacilityLocId
     * @return $this
     */
    public function setClearanceFacilityLocId($clearanceFacilityLocId)
    {
        $this->values['ClearanceFacilityLocId'] = $clearanceFacilityLocId;
        return $this;
    }
    /**
     * Identifies the type of clearance performed at the clearance facility.
     *
     * @param \FedEx\UploadDocumentService\SimpleType\DistributionClearanceType|string $clearanceType
     * @return $this
     */
    public function setClearanceType($clearanceType)
    {
        $this->values['ClearanceType'] = $clearanceType;
        return $this;
    }
    /**
     * Provides summary totals across all CRNs in a distribution.
     *
     * @param InternationalDistributionSummaryDetail $summaryDetail
     * @return $this
     */
    public function setSummaryDetail(\FedExVendor\FedEx\UploadDocumentService\ComplexType\InternationalDistributionSummaryDetail $summaryDetail)
    {
        $this->values['SummaryDetail'] = $summaryDetail;
        return $this;
    }
    /**
     * Specifies how charges relating to different aspects of a shipment are to be paid.
     *
     * @param SplitPaymentSpecification $splitPaymentSpecification
     * @return $this
     */
    public function setSplitPaymentSpecification(\FedExVendor\FedEx\UploadDocumentService\ComplexType\SplitPaymentSpecification $splitPaymentSpecification)
    {
        $this->values['SplitPaymentSpecification'] = $splitPaymentSpecification;
        return $this;
    }
}