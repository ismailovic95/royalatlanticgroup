<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies the information associated with a package that has COD special service in a ground shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property Money $CollectionAmount
 * @property \FedEx\UploadDocumentService\SimpleType\CodAdjustmentType|string $AdjustmentType
 * @property boolean $Electronic
 * @property PackageBarcodes $Barcodes
 * @property ShippingDocument $Label
 */
class CodReturnPackageDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CodReturnPackageDetail';
    /**
     * Set CollectionAmount
     *
     * @param Money $collectionAmount
     * @return $this
     */
    public function setCollectionAmount(\FedExVendor\FedEx\UploadDocumentService\ComplexType\Money $collectionAmount)
    {
        $this->values['CollectionAmount'] = $collectionAmount;
        return $this;
    }
    /**
     * Set AdjustmentType
     *
     * @param \FedEx\UploadDocumentService\SimpleType\CodAdjustmentType|string $adjustmentType
     * @return $this
     */
    public function setAdjustmentType($adjustmentType)
    {
        $this->values['AdjustmentType'] = $adjustmentType;
        return $this;
    }
    /**
     * Set Electronic
     *
     * @param boolean $electronic
     * @return $this
     */
    public function setElectronic($electronic)
    {
        $this->values['Electronic'] = $electronic;
        return $this;
    }
    /**
     * Set Barcodes
     *
     * @param PackageBarcodes $barcodes
     * @return $this
     */
    public function setBarcodes(\FedExVendor\FedEx\UploadDocumentService\ComplexType\PackageBarcodes $barcodes)
    {
        $this->values['Barcodes'] = $barcodes;
        return $this;
    }
    /**
     * Set Label
     *
     * @param ShippingDocument $label
     * @return $this
     */
    public function setLabel(\FedExVendor\FedEx\UploadDocumentService\ComplexType\ShippingDocument $label)
    {
        $this->values['Label'] = $label;
        return $this;
    }
}
