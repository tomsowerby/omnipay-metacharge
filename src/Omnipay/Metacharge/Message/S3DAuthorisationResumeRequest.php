<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * S3DAuthorisationResumeRequest.php
 *
 * Created on: 10/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class S3DAuthorisationResumeRequest extends AbstractRequest
{
    /**
     * getData.
     *
     * @throws InvalidRequestException
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData('S3DAUTH');

        $this->validate('instId', 'transactionId', 'securityToken', 's3DTransID', 's3DResponse', 's3DMerchantData');

        $data['intTransID'] = $this->getTransactionId();
        $data['strSecurityToken'] = $this->getSecurityToken();
        $data['strS3DTransID'] = $this->getS3DTransID();
        $data['strS3DResponse'] = $this->getS3DResponse();
        $data['strS3DMerchantData'] = $this->getS3DMerchantData();

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
     * getS3DTransID.
     *
     *
     * @return string
     */
    public function getS3DTransID()
    {
        return $this->getParameter('s3DTransID');
    }

    /**
     * setS3DTransID.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setS3DTransID($value)
    {
        return $this->setParameter('s3DTransID', $value);
    }

    /**
     * getS3DResponse.
     *
     *
     * @return string
     */
    public function getS3DResponse()
    {
        return $this->getParameter('s3DResponse');
    }

    /**
     * setS3DResponse.
     * The value of the "PaRes" attribute in the response from 3D Secure
     *
     * @param string $value
     *
     * @return $this
     */
    public function setS3DResponse($value)
    {
        return $this->setParameter('s3DResponse', $value);
    }

    /**
     * getS3DMerchantData.
     *
     *
     * @return string
     */
    public function getS3DMerchantData()
    {
        return $this->getParameter('s3DMerchantData');
    }

    /**
     * setS3DMerchantData.
     * The value of the "MD" attribute in the response from 3D Secure
     *
     * @param string $value
     *
     * @return $this
     */
    public function setS3DMerchantData($value)
    {
        return $this->setParameter('s3DMerchantData', $value);
    }
}
