<?php

namespace UpsFreeVendor\Ups\Entity;

trait ServiceSummaryTrait
{
    /** @deprecated */
    public $Service;
    /** @deprecated */
    public $Guaranteed;
    /** @deprecated */
    public $SaturdayDelivery;
    /** @deprecated */
    public $SaturdayDeliveryDisclaimer;
    /**
     * @var Service
     */
    protected $service;
    /**
     * @var Guaranteed
     */
    protected $guaranteed;
    /** @var mixed  */
    protected $saturdayDelivery;
    /** @var mixed  */
    protected $saturdayDeliveryDisclaimer;
    /**
     * @param \stdClass|null $response
     */
    public function build(\stdClass $response = null)
    {
        $this->setService(new \UpsFreeVendor\Ups\Entity\Service());
        $this->setGuaranteed(new \UpsFreeVendor\Ups\Entity\Guaranteed());
        if (null !== $response) {
            if (isset($response->Service)) {
                $this->setService(new \UpsFreeVendor\Ups\Entity\Service($response->Service));
            }
            if (isset($response->Guaranteed)) {
                $this->setGuaranteed(new \UpsFreeVendor\Ups\Entity\Guaranteed($response->Guaranteed));
            }
        }
    }
    /**
     * @return Service|null
     */
    public function getService()
    {
        return $this->service;
    }
    /**
     * @param Service $service
     */
    public function setService(\UpsFreeVendor\Ups\Entity\Service $service)
    {
        $this->Service = $service;
        $this->service = $service;
    }
    /**
     * @return Guaranteed|null
     */
    public function getGuaranteed()
    {
        return $this->guaranteed;
    }
    /**
     * @param $guaranteed
     */
    public function setGuaranteed(\UpsFreeVendor\Ups\Entity\Guaranteed $guaranteed)
    {
        $this->Guaranteed = $guaranteed;
        $this->guaranteed = $guaranteed;
    }
    /**
     * @return mixed
     */
    public function getSaturdayDelivery()
    {
        return $this->saturdayDelivery;
    }
    /**
     * @param mixed $saturdayDelivery
     */
    public function setSaturdayDelivery($saturdayDelivery)
    {
        $this->SaturdayDelivery = $saturdayDelivery;
        $this->saturdayDelivery = $saturdayDelivery;
    }
    /**
     * @return mixed
     */
    public function getSaturdayDeliveryDisclaimer()
    {
        return $this->saturdayDeliveryDisclaimer;
    }
    /**
     * @param mixed $saturdayDeliveryDisclaimer
     */
    public function setSaturdayDeliveryDisclaimer($saturdayDeliveryDisclaimer)
    {
        $this->SaturdayDeliveryDisclaimer = $saturdayDeliveryDisclaimer;
        $this->saturdayDeliveryDisclaimer = $saturdayDeliveryDisclaimer;
    }
}
