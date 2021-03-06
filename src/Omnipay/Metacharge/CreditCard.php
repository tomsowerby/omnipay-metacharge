<?php

namespace Omnipay\Metacharge;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Helper;

/**
 * CreditCard.php
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class CreditCard extends \Omnipay\Common\CreditCard
{
    /**
     * validate.
     *
     *
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function validate()
    {
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (is_null($value) || empty($value)) {
                throw new InvalidCreditCardException("The $key parameter is required");
            }
        }

        if ($this->getParameter('parentValidate') !== false) {
            parent::validate();
        } elseif (!is_null($this->getNumber()) && $this->getParameter('ignoreNumber') !== true) {
        //Check number is valid even if we aren't doing parent validation
            if (!Helper::validateLuhn($this->getNumber())) {
                throw new InvalidCreditCardException('Card number is invalid');
            }
        }
    }

    /**
     * setParentValidate.
     * Run default card validation on number, cvv and expiry.
     *
     * @param $value
     *
     */
    public function setParentValidate($value)
    {
        $this->setParameter('parentValidate', $value);
    }

    /**
     * setIgnoreNumber.
     * Used for 3DS tests, only testable using invalid card number.
     *
     * @param $value
     *
     */
    public function setIgnoreNumber($value)
    {
        $this->setParameter('ignoreNumber', $value);
    }

    /**
     * getFax.
     *
     *
     * @return string
     */
    public function getFax()
    {
        return $this->getParameter('billingFax');
    }

    /**
     * setFax.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setFax($value)
    {
        $this->setParameter('billingFax', $value);
        $this->setParameter('shippingFax', $value);

        return $this;
    }

    /**
     * getBillingFax.
     *
     *
     * @return string
     */
    public function getBillingFax()
    {
        return $this->getParameter('billingFax');
    }

    /**
     * setBillingFax.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setBillingFax($value)
    {
        return $this->setParameter('billingFax', $value);
    }

    /**
     * getShippingFax.
     *
     *
     * @return string
     */
    public function getShippingFax()
    {
        return $this->getParameter('shippingFax');
    }

    /**
     * setShippingFax.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setShippingFax($value)
    {
        return $this->setParameter('shippingFax', $value);
    }
}
