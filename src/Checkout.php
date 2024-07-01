<?php

namespace WorldlineInd\CheckoutJS;

class Checkout
{
    private $merchantCode;
    private $currency;
    private $salt;

    public function __construct($merchantCode, $currency, $salt)
    {
        $this->merchantCode = $merchantCode;
        $this->currency = $currency;
        $this->salt = $salt;
    }

    public function processPayment($data)
    {
        // Add logic to process payment
    }

    // Add other necessary methods
}
