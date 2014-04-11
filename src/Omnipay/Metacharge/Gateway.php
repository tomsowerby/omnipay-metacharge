<?php

namespace Omnipay\Metacharge;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway.php
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class Gateway extends AbstractGateway
{
    /**
     * getName.
     *
     *
     * @return string
     */
    public function getName()
    {
        return 'Metacharge (PayPoint.net)';
    }

    /**
     * getDefaultParameters.
     *
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'instId' => '',
            'sharedKey' => null,
            'testMode' => false,
            '3DSecureResponseUrl' => null,
            'accountId' => null,
        );
    }


    /**
     * getInstId.
     *
     *
     * @return int
     */
    public function getInstId()
    {
        return $this->getParameter('instId');
    }

    /**
     * setInstId.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setInstId($value)
    {
        return $this->setParameter('instId', $value);
    }

    /**
     * getSharedKey.
     *
     *
     * @return string
     */
    public function getSharedKey()
    {
        return $this->getParameter('sharedKey');
    }

    /**
     * setSharedKey.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSharedKey($value)
    {
        return $this->setParameter('sharedKey', $value);
    }

    /**
     * get3DSecureResponseUrl.
     *
     *
     * @return string
     */
    public function get3DSecureResponseUrl()
    {
        return $this->getParameter('3DSecureResponseUrl');
    }

    /**
     * set3DSecureResponseUrl.
     *
     * @param string $value
     *
     * @return $this
     */
    public function set3DSecureResponseUrl($value)
    {
        return $this->setParameter('3DSecureResponseUrl', $value);
    }

    //Payment, Payout, FraudGuard
    /**
     * getAccountId.
     *
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * setAccountId.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    /**
     * purchase.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\PaymentRequest', $parameters);
    }

    /**
     * refund.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\RefundRequest', $parameters);
    }

    /**
     * payout.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function payout(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\PayoutRequest', $parameters);
    }

    /**
     * repeatPayment.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function repeatPayment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\RepeatPaymentRequest', $parameters);
    }

    /**
     * preAuthCapture.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function preAuthCapture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\PreAuthCaptureRequest', $parameters);
    }

    /**
     * preAuthVoid.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function preAuthVoid(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\PreAuthVoidRequest', $parameters);
    }

    /**
     * subscriptionCancellation.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function subscriptionCancellation(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\SubscriptionCancellationRequest', $parameters);
    }

    /**
     * transactionConfirm.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function transactionConfirm(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\TransactionConfirmRequest', $parameters);
    }

    /**
     * fraudGuardCheck.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function fraudGuardCheck(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\FraudGuardCheckRequest', $parameters);
    }

    /**
     * s3DAuthorisationResume.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function s3DAuthorisationResume(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Metacharge\Message\S3DAuthorisationResumeRequest', $parameters);
    }
}
