<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService;

/**
 * A class that defines Canada Post services.
 */
class CanadaPostServices
{
    /**
     * @return array
     */
    private function get_services() : array
    {
        return ['domestic_ca' => ['DOM.EP' => \__('Expedited Parcel', 'octolize-canada-post-shipping'), 'DOM.RP' => \__('Regular Parcel', 'octolize-canada-post-shipping'), 'DOM.PC' => \__('Priority', 'octolize-canada-post-shipping'), 'DOM.XP' => \__('Xpresspost', 'octolize-canada-post-shipping'), 'DOM.LIB' => \__('Library Materials', 'octolize-canada-post-shipping'), 'USA.EP' => \__('Expedited Parcel USA', 'octolize-canada-post-shipping')], 'international_us' => ['USA.PW.ENV' => \__('Priority Worldwide envelope USA', 'octolize-canada-post-shipping'), 'USA.PW.PAK' => \__('Priority Worldwide pak USA', 'octolize-canada-post-shipping'), 'USA.PW.PARCEL' => \__('Priority Worldwide parcel USA', 'octolize-canada-post-shipping'), 'USA.XP' => \__('Xpresspost USA', 'octolize-canada-post-shipping'), 'USA.EP' => \__('Expedited Parcel USA', 'octolize-canada-post-shipping'), 'USA.TP' => \__('Tracked Packet - USA', 'octolize-canada-post-shipping'), 'USA.SP.AIR' => \__('Small Packet USA Air', 'octolize-canada-post-shipping')], 'international' => ['INT.PW.ENV' => \__('Priority Worldwide envelope INT\'L', 'octolize-canada-post-shipping'), 'INT.PW.PAK' => \__('Priority Worldwide pak INT\'L', 'octolize-canada-post-shipping'), 'INT.PW.PARCEL' => \__('Priority Worldwide parcel INT\'L', 'octolize-canada-post-shipping'), 'INT.XP' => \__('Xpresspost International', 'octolize-canada-post-shipping'), 'INT.IP.SURF' => \__('International Parcel Surface', 'octolize-canada-post-shipping'), 'INT.IP.AIR' => \__('International Parcel Air', 'octolize-canada-post-shipping'), 'INT.TP' => \__('Tracked Packet - International', 'octolize-canada-post-shipping'), 'INT.SP.SURF' => \__('Small Packet International Surface', 'octolize-canada-post-shipping'), 'INT.SP.AIR' => \__('Small Packet International Air', 'octolize-canada-post-shipping')]];
    }
    public function get_all_services()
    {
        return \array_merge($this->get_services_domestic_ca(), $this->get_services_us(), $this->get_services_international());
    }
    /**
     * @return array
     */
    public function get_services_domestic_ca() : array
    {
        return $this->get_services()['domestic_ca'];
    }
    /**
     * @return array
     */
    public function get_services_us() : array
    {
        return $this->get_services()['international_us'];
    }
    /**
     * @return array
     */
    public function get_services_international() : array
    {
        return $this->get_services()['international'];
    }
}
