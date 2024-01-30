<?php

namespace UpsFreeVendor\WPDesk\Forms;

use UpsFreeVendor\Psr\Container\ContainerInterface;
/**
 * Some field owners can receive and process field data.
 * Probably should be used with FieldProvider interface.
 *
 * @package WPDesk\Forms
 */
interface FieldsDataReceiver
{
    /**
     * Set values corresponding to fields.
     *
     * @return void
     */
    public function update_fields_data(\UpsFreeVendor\Psr\Container\ContainerInterface $data);
}
