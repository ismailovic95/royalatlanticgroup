<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ReturnShipmentDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property \FedEx\OpenShipService\SimpleType\ReturnType|string $ReturnType
 * @property Rma $Rma
 * @property ReturnEMailDetail $ReturnEMailDetail
 * @property ReturnAssociationDetail $ReturnAssociation
 */
class ReturnShipmentDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ReturnShipmentDetail';
    /**
     * Set ReturnType
     *
     * @param \FedEx\OpenShipService\SimpleType\ReturnType|string $returnType
     * @return $this
     */
    public function setReturnType($returnType)
    {
        $this->values['ReturnType'] = $returnType;
        return $this;
    }
    /**
     * Set Rma
     *
     * @param Rma $rma
     * @return $this
     */
    public function setRma(\FedExVendor\FedEx\OpenShipService\ComplexType\Rma $rma)
    {
        $this->values['Rma'] = $rma;
        return $this;
    }
    /**
     * Set ReturnEMailDetail
     *
     * @param ReturnEMailDetail $returnEMailDetail
     * @return $this
     */
    public function setReturnEMailDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\ReturnEMailDetail $returnEMailDetail)
    {
        $this->values['ReturnEMailDetail'] = $returnEMailDetail;
        return $this;
    }
    /**
     * Set ReturnAssociation
     *
     * @param ReturnAssociationDetail $returnAssociation
     * @return $this
     */
    public function setReturnAssociation(\FedExVendor\FedEx\OpenShipService\ComplexType\ReturnAssociationDetail $returnAssociation)
    {
        $this->values['ReturnAssociation'] = $returnAssociation;
        return $this;
    }
}
