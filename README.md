# PHP CheckoutJS Integration

[![Latest Stable Version](https://poser.pugx.org/worldline-ind/php-checkoutjs/v/stable)](https://packagist.org/packages/worldline-ind/php-checkoutjs)
[![Total Downloads](https://poser.pugx.org/worldline-ind/php-checkoutjs/downloads)](https://packagist.org/packages/worldline-ind/php-checkoutjs)
[![License](https://poser.pugx.org/worldline-ind/php-checkoutjs/license)](https://packagist.org/packages/worldline-ind/php-checkoutjs)

PHP CheckoutJS integration for Worldline payment gateway.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Basic Usage](#basic-usage)
  - [Initialize the Checkout Class](#initialize-the-checkout-class)
  - [Process Payment Request](#process-payment-request)
  - [Handle Payment Response](#handle-payment-response)
- [Advanced Usage](#advanced-usage)
  - [Reconciliation Request](#reconciliation-request)
  - [Refund Request](#refund-request)
  - [Server-to-Server Communication](#server-to-server-communication)
  - [eMandate and Standing Instruction](#emandate-and-standing-instruction)
- [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

## Installation

You can install the package via Composer:

```bash
composer require worldline-ind/php-checkoutjs

Configuration
Ensure you have a worldline_AdminData.json file with the required configuration settings in your project root. Here is an example:

json
Copy code
{
    "merchantCode": "YOUR_MERCHANT_CODE",
    "merchantSchemeCode": "YOUR_SCHEME_CODE",
    "salt": "YOUR_SALT",
    "typeOfPayment": "TEST",
    "currency": "INR",
    "primaryColor": "#000000",
    "secondaryColor": "#FFFFFF",
    "buttonColor1": "#0000FF",
    "buttonColor2": "#FFFFFF",
    "logoURL": "https://yourdomain.com/logo.png",
    "enableExpressPay": true,
    "separateCardMode": false,
    "enableNewWindowFlow": true,
    "merchantMessage": "Thank you for your order!",
    "disclaimerMessage": "Please review your order before proceeding.",
    "paymentMode": "ALL",
    "paymentModeOrder": "NB,CARD,UPI",
    "enableInstrumentDeRegistration": false,
    "transactionType": "SALE",
    "hideSavedInstruments": false,
    "saveInstrument": false,
    "displayTransactionMessageOnPopup": false,
    "embedPaymentGatewayOnPage": false,
    "enableEmandate": false,
    "hideSIConfirmation": false,
    "expandSIDetails": false,
    "enableDebitDay": false,
    "showSIResponseMsg": false,
    "showSIConfirmation": false,
    "enableTxnForNonSICards": false,
    "showAllModesWithSI": false,
    "enableSIDetailsAtMerchantEnd": false
}
Basic Usage
Initialize the Checkout Class
Include the Composer autoloader and initialize the Checkout class with your merchant details.

php
Copy code
<?php

require 'vendor/autoload.php';

use WorldlineInd\CheckoutJS\Checkout;

$checkout = new Checkout("YOUR_MERCHANT_CODE", "INR", "YOUR_SALT");

?>
Process Payment Request
To process a payment request, gather the necessary data from the client and pass it to the processPayment method of the Checkout class.

php
Copy code
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'mrctCode' => $_POST['mrctCode'],
        'txn_id' => $_POST['txn_id'],
        'amount' => $_POST['amount'],
        'custID' => $_POST['custID'],
        'mobNo' => $_POST['mobNo'],
        'email' => $_POST['email'],
        'returnUrl' => $_POST['returnUrl'],
    ];
    
    $checkout->processPayment($data);
}

?>
Handle Payment Response
After processing the payment, handle the payment response and verify the hash to ensure the response is authentic.

php
Copy code
<?php

$response = $_POST;
$verified = $checkout->verifyResponse($response);

if ($verified) {
    if ($response['statusCode'] == '0300') {
        echo "Transaction Successful";
    } else {
        echo "Transaction Failed";
    }
} else {
    echo "Invalid response hash";
}

?>
Advanced Usage
Reconciliation Request
Handle reconciliation requests to verify transactions for a specific period.

php
Copy code
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transactionIdentifier = $_POST['transactionIdentifier'];
    $fromDate = $_POST['fromtransactionDate'];
    $toDate = $_POST['totransactionDate'];

    $reconciliationData = $checkout->reconcile($transactionIdentifier, $fromDate, $toDate);
    // Process reconciliation data
}

?>
Refund Request
Handle refund requests for transactions.

php
Copy code
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'mrctCode' => $_POST['mrctCode'],
        'txn_id' => $_POST['txn_id'],
        'amount' => $_POST['amount'],
        'SALT' => $_POST['SALT'],
    ];

    $refundData = $checkout->refund($data);
    // Process refund data
}

?>
Server-to-Server Communication
Handle server-to-server communication to verify transaction status.

php
Copy code
<?php

$response = $_POST;
$verified = $checkout->verifyS2SResponse($response);

if ($verified) {
    if ($response['statusCode'] == '0300') {
        echo "Transaction Successful";
    } elseif ($response['statusCode'] == '0398') {
        echo "Transaction Initiated";
    } else {
        echo "Transaction Failed";
    }
} else {
    echo "Invalid response hash";
}

?>
eMandate and Standing Instruction
Handle eMandate and standing instruction requests.

php
Copy code
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'txn_id' => $_POST['txn_id'],
        // Additional required data
    ];

    $mandateVerificationData = $checkout->verifyMandate($data);
    // Process mandate verification data
}

?>
