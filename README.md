# Omnipay: Metacharge

**Metacharge (by Paypoint.net) driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/tomsowerby/omnipay-metacharge.png?branch=master)](https://travis-ci.org/tomsowerby/omnipay-metacharge)
[![Latest Stable Version](https://poser.pugx.org/omnipay/metacharge/version.png)](https://packagist.org/packages/omnipay/metacharge)
[![Total Downloads](https://poser.pugx.org/omnipay/metacharge/d/total.png)](https://packagist.org/packages/omnipay/metacharge)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Paypoint.net Metacharge Gateway Freedom +IMA support for Omnipay.

Based on Paypoint.net Gateway +IMA [Integration Guides](http://www.paypoint.net/support/integration-guides/):
[Gateway Freedom +IMA Integration Guide 2.3](http://www.paypoint.net/assets/guides/MCPE_Freedom+IMA_2.3.pdf)
[Gateway Freedom +IMA 3D Secure Guide 3.1](http://www.paypoint.net/assets/guides/MCPE_Freedom+IMA_3DSecure_3.1.pdf)

This has been created based on the documentation, and has not been fully tested yet. Changes will be made as it is tested so keep an eye on revisions.

*NOTE*
An active account is required for 3D secure integration (not mentioned in 3D Secure documentation - at present version 3.1).
The test account always returns results as though the card is not enrolled.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "omnipay/metacharge": "1.0.*@dev"
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
        'number' => '4111111111111111',
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

    // Is it a 3d secure redirect?
    if($response->isRedirect()) {
        //Save bits for later.
        $transactionId = $response->getTransactionId();
        $secureToken = $response->getSecurityToken();
        $s3DTransId = $response->getS3DTransID();
        $s3DMerchantData = $response->getS3DMerchantData();
        //Save these

        //Send user off
        $response->redirect();
    }

    /**
     * On your 3d secure response capture page
     */

    // A lot of these are the values that we saved above
    $s3dParams = array(
        'transactionId' => $transactionId,
        'securityToken' => $secureToken,
        's3DTransID' => $s3DTransId,
        's3DResponse' => $_POST['PaRes'],
        's3DMerchantData' => $s3DMerchantData, //Or $_POST['MD'] should be the same
    );

    $request = $gateway->s3DAuthorisationResume($s3dParams); /* @var $request \Omnipay\Metacharge\Message\S3DAuthorisationResumeRequest */

    $response = $request->send(); /* @var $response \Omnipay\Metacharge\Message\PaymentResponse */

    // Is it a success, after 3D secure?
    var_dump($response->isSuccessful()); // bool
    var_dump($response->getData()); // array


## Todo

* Pass through parameters. Supported by Metacharge using prefix "PT_"
* Unit tests cover everything, but could be more extensive. Please report bugs and ideally provide a failing test.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/tomsowerby/omnipay-metacharge/issues),
or better yet, fork the library and submit a pull request.
