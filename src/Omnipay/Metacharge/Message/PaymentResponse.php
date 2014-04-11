<?php

namespace Omnipay\Metacharge\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PaymentResponse.php
 *
 * Created on: 01/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class PaymentResponse extends AbstractCardResponse implements RedirectResponseInterface
{
    /**
     * getSecurityToken.
     *
     * A value that must be stored alongside each transaction in your system. This value will be required
     * when performing an operation that references a previous transaction – a refund, for example
     *
     * @return string|null
     */
    public function getSecurityToken()
    {
        return array_key_exists('strSecurityToken', $this->data) ? $this->data['strSecurityToken'] : null;
    }

    /**
     * getAVS.
     *
     * The result of the AVS check performed for this transaction. This field will be omitted if the
     * check was not performed. performed Values: 0=AVS check failed, 1=AVS check passed.
     *
     * @return bool|null
     */
    public function getAVS()
    {
        return array_key_exists('intAVS', $this->data) ? $this->data['intAVS'] : null;
    }

    /**
     * getCV2.
     *
     * The result of the CV2 check performed for this transaction. This field will be omitted if the
     * check was not performed. performed Values: 0=CV2 check failed, 1=CV2 check passed.
     *
     * @return bool|null
     */
    public function getCV2()
    {
        return array_key_exists('intCV2', $this->data) ? $this->data['intCV2'] : null;
    }

    /**
     * get3DSResult.
     *
     * For Merchants using 3D Secure via our MPI, you can optionally have us return the outcome of
     * the 3D Secure check once decoded by our systems. Please first contact our support team via
     * completesupport@paypoint.net to request that this is enabled. The possible values returned are:
     * UNCHECKED, PASS, FAIL, BYPASS, ENROLLED, ENROLFAIL, INELIGIBLE. Please see the MCPE
     * Bank Enterprise 3D Secure supplement for further details on 3D Secure and these outcomes.
     *
     * @return string|null
     */
    public function get3DSResult()
    {
        return array_key_exists('str3DSResult', $this->data) ? $this->data['str3DSResult'] : null;
    }

    /**
     * getScheduleId.
     *
     * The MCPE unique identifier for any payment schedule associated with this transaction (if applicable).
     *
     * @return int|null
     */
    public function getScheduleId()
    {
        return array_key_exists('intScheduleID', $this->data) ? $this->data['intScheduleID'] : null;
    }




    /**
     * 3D Secure Redirect functions.
     * Documented in the 3D Secure section of the MCPE integration guides
     * Data will only be available if intStatus == 2.
     */

    /**
     * isRedirect.
     *
     * Is the intStatus = 2, then it's a 3dSecure redirect
     *
     * @return bool
     */
    public function isRedirect()
    {
        return array_key_exists('intStatus', $this->data) &&
        ($this->data['intStatus'] == 2) &&
        ($this->request instanceof PaymentRequest);
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        //Should be set if redirecting

        return $this->data['strS3DURL'];
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return array
     */
    public function getRedirectData()
    {
        //All of these should be set if we're redirecting and coming from a Payment Request

        $data = array();
        $data['PaReq'] = $this->data['strS3DRequest'];
        $data['MD'] = $this->getS3DMerchantData();

        $request = $this->request; /* @var $request PaymentRequest */
        $data['TermUrl'] = $request->get3DSecureResponseUrl();

        return $data;
    }

    /**
     * getS3DTransID.
     *
     * The MCPE unique identifier for this 3D Secure authentication.
     * Used to resume the payment.
     *
     * @return string|null
     */
    public function getS3DTransID()
    {
        return array_key_exists('strS3DTransID', $this->data) ? $this->data['strS3DTransID'] : null;
    }

    /**
     * getS3DMerchantData.
     *
     * Aggregated merchant data which is passed through the authentication.
     *
     * @return string|null
     */
    public function getS3DMerchantData()
    {
        return array_key_exists('strS3DMerchantData', $this->data) ? $this->data['strS3DMerchantData'] : null;
    }
}
