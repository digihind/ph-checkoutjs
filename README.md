  PHP CheckoutJS Integration Documentation

PHP CheckoutJS Integration
==========================

Integrate your PHP applications with Worldline's CheckoutJS to seamlessly handle payments.

Badges
------

 [![Latest Stable Version](https://poser.pugx.org/worldline-ind/php-checkoutjs/v/stable)](https://packagist.org/packages/worldline-ind/php-checkoutjs)[![Total Downloads](https://poser.pugx.org/worldline-ind/php-checkoutjs/downloads) ](https://packagist.org/packages/worldline-ind/php-checkoutjs)[![License](https://poser.pugx.org/worldline-ind/php-checkoutjs/license)](https://packagist.org/packages/worldline-ind/php-checkoutjs)

Table of Contents
-----------------

1.  [Installation](#installation)
2.  [Configuration](#configuration)
3.  [Usage](#usage)
    *   [Basic Usage](#basic-usage)
    *   [Advanced Features](#advanced-features)
4.  [Examples](#examples)
5.  [Testing](#testing)
6.  [Contributing](#contributing)
7.  [License](#license)

Installation
------------

Install the package via Composer:

`composer require worldline-ind/php-checkoutjs`

Configuration
-------------

Place the `worldline_AdminData.json` file in your project root with the following contents:

    {
        "merchantCode": "YOUR_MERCHANT_CODE",
        "merchantSchemeCode": "YOUR_SCHEME_CODE",
        "salt": "YOUR_SALT",
        "typeOfPayment": "TEST",
        "currency": "INR",
        ...
    }

Usage
-----

### Basic Usage

Initialize the Checkout Class:

`<?php require 'vendor/autoload.php'; use WorldlineInd\CheckoutJS\Checkout; $checkout = new Checkout("YOUR_MERCHANT_CODE", "INR", "YOUR_SALT"); ?>`

Process Payment Request:

`<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { $data = [ 'mrctCode' => $_POST['mrctCode'], 'txn_id' => $_POST['txn_id'], 'amount' => $_POST['amount'], ... ]; $checkout->processPayment($data); } ?>`

Handle Payment Response:

`<?php $response = $_POST; $verified = $checkout->verifyResponse($response); if ($verified) { if ($response['statusCode'] == '0300') { echo "Transaction Successful"; } else { echo "Transaction Failed"; } } else { echo "Invalid response hash"; } ?>`

### Advanced Features

Explore additional functionalities such as refunds, reconciliation, and server-to-server communication within the documentation.

Examples
--------

View full examples in the `/examples` directory.

Testing
-------

Run the following command to execute tests:

`phpunit`

Contributing
------------

Contributions are welcome! Please see `CONTRIBUTING.md` for more details.

License
-------

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
