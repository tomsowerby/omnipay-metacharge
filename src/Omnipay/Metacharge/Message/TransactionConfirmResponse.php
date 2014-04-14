<?php

namespace Omnipay\Metacharge\Message;

/**
 * TransactionConfirmResponse.php
 *
 * Created on: 02/04/14
 *
 * @package Omnipay\Metacharge
 * @author  Thomas Sowerby <email@tomsowerby.com>
 */
class TransactionConfirmResponse extends Response
{
    /**
     * getResponse.
     *
     * If a valid transaction is found matching the unique identifier provided in strCartID, this
     * parameter will return a URL-encoded string containing all relevant API response fields
     * that would have been returned in response to the original transaction request. Please
     * refer to each of the prior API response sections to review the format of these.
     *
     * @todo this should be changed to return a Response object that matches the original sent.
     *
     * @return array|null
     */
    public function getResponse()
    {
        if (!array_key_exists('strResponse', $this->data)) {
            return null;
        }

        $responseArray = array();
        parse_str(urldecode($this->data['strResponse']), $responseArray);

        return $responseArray;
    }

    /**
     * getResponseArray.
     *
     * @todo Once getResponse is returning a response object, as intended, use it's code here for backwards compatibility.
     *
     * @return array|null
     */
    public function getResponseArray()
    {
        return $this->getResponse();
    }
}
