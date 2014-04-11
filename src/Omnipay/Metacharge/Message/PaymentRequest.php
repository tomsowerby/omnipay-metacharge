<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;

/**
 * PaymentRequest.php
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class PaymentRequest extends AbstractCardRequest
{
    protected $transType = 'PAYMENT';
    protected $requiredParams = array(
        'instId',
        'cartId',
        'description',
        'amount',
        'currency',
        'card',
        '3DSecureResponseUrl'
    );
    //number and expiry handled by default
    protected $requiredCardParams = array('billingFirstName', 'billingLastName', 'email', 'billingPostcode', 'cvv');

    /**
     * getData.
     *
     * @throws InvalidCreditCardException
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        //User
        $data['strUserIP'] = $this->getClientIp();

        //Fullfillment Date
        if (!is_null($this->getFulfillmentDay()) &&
            !is_null($this->getFulfillmentMonth()) &&
            !is_null($this->getFulfillmentYear())) {
            $data['datFulfillment'] = $this->getFulfillmentDate("d/m/Y");
        }

        //Scheduled
        $data['fltSchAmount'] = $this->getScheduleAmount();
        $data['strSchPeriod'] = $this->getSchedulePeriod();
        $data['intRecurs'] = $this->getRecurs();
        $data['intCancelAfter'] = $this->getCancelAfter();

        /*
         * A value to indicate the type of authorisation to use. If this field is
         * omitted, full authorisation with capture is assumed. Values:
         * 0=equivalent to field omitted,
         * 1=authorisation with capture,
         * 2=pre-authorisation only
         * (see section 3.4)
         */
        $data['intAuthMode'] = $this->getAuthMode();

        $data = $this->cleanNullData($data);

        return $data;
    }

    /**
     * createResponse.
     *
     * @param $data
     *
     * @return PaymentResponse|Response
     */
    protected function createResponse($data)
    {
        return $this->response = new PaymentResponse($this, $data);
    }

    /**
     * getAuthMode.
     *
     *
     * @return int
     */
    public function getAuthMode()
    {
        return $this->getParameter('authMode');
    }

    /**
     * setAuthMode.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setAuthMode($value)
    {
        return $this->setParameter('authMode', $value);
    }

    /**
     * getFulfillmentDay.
     *
     *
     * @return int
     */
    public function getFulfillmentDay()
    {
        return $this->getParameter('fulfillmentDay');
    }

    /**
     * setFulfillmentDay.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setFulfillmentDay($value)
    {
        return $this->setParameter('fulfillmentDay', $value);
    }

    /**
     * getFulfillmentMonth.
     *
     *
     * @return int
     */
    public function getFulfillmentMonth()
    {
        return $this->getParameter('fulfillmentMonth');
    }

    /**
     * setFulfillmentMonth.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setFulfillmentMonth($value)
    {
        return $this->setParameter('fulfillmentMonth', $value);
    }

    /**
     * getFulfillmentYear.
     *
     *
     * @return int
     */
    public function getFulfillmentYear()
    {
        return $this->getParameter('fulfillmentYear');
    }

    /**
     * setFulfillmentYear.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setFulfillmentYear($value)
    {
        return $this->setParameter('fulfillmentYear', $value);
    }

    /**
     * getFulfillmentDate.
     *
     * @param string $format
     *
     * @return string
     */
    public function getFulfillmentDate($format)
    {
        return gmdate(
            $format,
            gmmktime(0, 0, 0, $this->getFulfillmentMonth(), $this->getFulfillmentDay(), $this->getFulfillmentYear())
        );
    }

    /**
     * getScheduleAmount.
     *
     *
     * @return float
     */
    public function getScheduleAmount()
    {
        return $this->getParameter('scheduleAmount');
    }

    /**
     * setScheduleAmount.
     * For scheduled payments based upon this transaction, the amount
     * associated with each scheduled payment, in the currency specified
     * in the strCurrency field, formatted as for the fltAmount field.
     *
     * @param float $value
     *
     * @return $this
     */
    public function setScheduleAmount($value)
    {
        return $this->setParameter('scheduleAmount', $value);
    }

    /**
     * getSchedulePeriod.
     * For scheduled payments based upon this transaction, the interval
     * between payments, given as XY where X is a number (1-999) and Y
     * is “D” for days, “W” for weeks or “M” for months.
     *
     * @return string
     */
    public function getSchedulePeriod()
    {
        return $this->getParameter('schedulePeriod');
    }

    /**
     * setSchedulePeriod.
     * For scheduled payments based upon this transaction, the interval
     * between payments, given as XY where X is a number (1-999) and Y
     * is “D” for days, “W” for weeks or “M” for months.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSchedulePeriod($value)
    {
        return $this->setParameter('schedulePeriod', $value);
    }

    /**
     * getRecurs.
     *
     *
     * @return int
     */
    public function getRecurs()
    {
        return $this->getParameter('recurs');
    }

    /**
     * setRecurs.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setRecurs($value)
    {
        return $this->setParameter('recurs', $value);
    }

    /**
     * getCancelAfter.
     *
     *
     * @return int
     */
    public function getCancelAfter()
    {
        return $this->getParameter('cancelAfter');
    }

    /**
     * setCancelAfter.
     * Cancel a subscription after this many successful payments.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setCancelAfter($value)
    {
        return $this->setParameter('cancelAfter', $value);
    }
}
