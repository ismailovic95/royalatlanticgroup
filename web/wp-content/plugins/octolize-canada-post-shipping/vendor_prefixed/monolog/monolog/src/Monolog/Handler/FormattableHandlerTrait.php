<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OctolizeShippingCanadaPostVendor\Monolog\Handler;

use OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface;
use OctolizeShippingCanadaPostVendor\Monolog\Formatter\LineFormatter;
/**
 * Helper trait for implementing FormattableInterface
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
trait FormattableHandlerTrait
{
    /**
     * @var ?FormatterInterface
     */
    protected $formatter;
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface $formatter) : \OctolizeShippingCanadaPostVendor\Monolog\Handler\HandlerInterface
    {
        $this->formatter = $formatter;
        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }
        return $this->formatter;
    }
    /**
     * Gets the default formatter.
     *
     * Overwrite this if the LineFormatter is not a good default for your handler.
     */
    protected function getDefaultFormatter() : \OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface
    {
        return new \OctolizeShippingCanadaPostVendor\Monolog\Formatter\LineFormatter();
    }
}
