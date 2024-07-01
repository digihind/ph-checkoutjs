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
