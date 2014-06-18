# Omnipay: Metacharge

**Metacharge (by Paypoint.net) driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/tomsowerby/omnipay-metacharge.png?branch=master)](https://travis-ci.org/tomsowerby/omnipay-metacharge)
[![Coverage Status](https://coveralls.io/repos/tomsowerby/omnipay-metacharge/badge.png?branch=master)](https://coveralls.io/r/tomsowerby/omnipay-metacharge?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/tomsowerby/omnipay-metacharge/badges/quality-score.png?s=45f5b73c368564ea44670f1353582e52937065c1)](https://scrutinizer-ci.com/g/tomsowerby/omnipay-metacharge/)
[![Dependency Status](https://www.versioneye.com/user/projects/5347fcf4fe0d0739ac00029d/badge.png)](https://www.versioneye.com/user/projects/5347fcf4fe0d0739ac00029d)

[![Latest Stable Version](https://poser.pugx.org/omnipay/metacharge/version.png)](https://packagist.org/packages/omnipay/metacharge)
[![Total Downloads](https://poser.pugx.org/omnipay/metacharge/d/total.png)](https://packagist.org/packages/omnipay/metacharge)
[![Latest Unstable Version](https://poser.pugx.org/omnipay/metacharge/v/unstable.png)](https://packagist.org/packages/omnipay/metacharge)
[![License](https://poser.pugx.org/omnipay/metacharge/license.png)](https://packagist.org/packages/omnipay/metacharge)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Paypoint.net Metacharge Gateway Freedom +IMA support for Omnipay.

Based on Paypoint.net Gateway +IMA [Integration Guides](http://www.paypoint.net/support/integration-guides/):
* [Gateway Freedom +IMA Integration Guide 2.3](http://www.paypoint.net/assets/guides/MCPE_Freedom+IMA_2.3.pdf)
* [Gateway Freedom +IMA 3D Secure Guide 3.1](http://www.paypoint.net/assets/guides/MCPE_Freedom+IMA_3DSecure_3.1.pdf)

This has been created based on the documentation, and has not been fully tested yet. Changes will be made as it is tested so keep an eye on revisions.

If you are looking for the Paypoint Secpay Freedom product, a library seems to be in development by JustinBusschau [here](https://github.com/JustinBusschau/omnipay-secpay).

*NOTE*
An active account is required for 3D secure integration (not mentioned in 3D Secure documentation - at present version 3.1).
Also, 3D secure needs to be activated on the account by Secpay staff.
An account in test mode, or without 3D secure activated, always returns results as though the card is not enrolled.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "omnipay/metacharge": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Metacharge (Paypoint.net Metacharge Checkout) with 3DSecure (API v1.4)

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

### Standard payment with 3D secure

    /**
     * On your submission page
     */

    $gateway = \Omnipay\Omnipay::create('Metacharge'); /* @var $gateway \Omnipay\Metacharge\Gateway */
    $gateway->setTestMode(1);
    $gateway->setInstId(123456); //Edit to your install id
    $gateway->set3DSecureResponseUrl('http://www.yoursite.com/response'); //Set to your 3d secure response capture endpoint

    $formInputData = array(
        'firstName' => 'Joe',
        'lastName' => 'Bloggs',
        'email' => 'test@paypoint.net',
        'postcode' => 'BA12BU',
        'number' => '1234123412341234', // This number MUST be used for 3D secure testing (based on conversation with Secpay technical team).
        'expiryMonth' => '06',
        'expiryYear' => '14',
        'cvv' => '707',
    );
    $card = new \Omnipay\Common\CreditCard($formInputData);

    $requestParams = array(
        'amount' => 10.00,
        'currency' => 'GBP',
        'card' => $card,
        'cartID' => '654321',
        'description' => 'description of goods',
    );

    $request = $gateway->purchase($requestParams); /* @var $request \Omnipay\Metacharge\Message\PaymentRequest */

    $response = $request->send(); /* @var $response \Omnipay\Metacharge\Message\PaymentResponse */

    // Is it an immediate success, with no 3D secure?
    var_dump($response->isSuccessful()); // bool
    var_dump($response->getData()); // array
    // Process the payment here.

    // Is it a 3d secure redirect?
    if($response->isRedirect()) {
        //Save bits for later.
        $transactionId = $response->getTransactionId();
        $secureToken = $response->getSecurityToken();
        $s3DTransId = $response->getS3DTransID();
        $s3DMerchantData = $response->getS3DMerchantData();
        //Save these

        //Send user off to the 3D secure page
        $response->redirect();
    }

    /**
     * On your 3d secure response capture page, set by $gateway->set3DSecureResponseUrl
     */

    // A lot of these are the values that we saved above
    $s3dParams = array(
        'transactionId' => $transactionId,
        'securityToken' => $secureToken,
        's3DTransID' => $s3DTransId,
        's3DResponse' => $_POST['PaRes'],
        's3DMerchantData' => $s3DMerchantData, //Or $_POST['MD'] should be the same
    );

    // Any validation of the session that you might need to do here.

    // Resume the request
    $request = $gateway->s3DAuthorisationResume($s3dParams); /* @var $request \Omnipay\Metacharge\Message\S3DAuthorisationResumeRequest */

    $response = $request->send(); /* @var $response \Omnipay\Metacharge\Message\PaymentResponse */

    // Is it a success, after 3D secure? Note, this response is the same as an initially successful payment.
    var_dump($response->isSuccessful()); // bool
    var_dump($response->getData()); // array


## Todo

* Pass through parameters. Supported by Metacharge using prefix "PT_"
* Unit tests cover everything, but could be more extensive. Please report bugs and ideally provide a failing test.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release announcements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/tomsowerby/omnipay-metacharge/issues),
or better yet, fork the library and submit a pull request.
