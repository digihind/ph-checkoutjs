<?php

use PHPUnit\Framework\TestCase;
use WorldlineInd\CheckoutJS\Checkout;

class CheckoutTest extends TestCase
{
    public function testProcessPayment()
    {
        $checkout = new Checkout("merchantCode", "INR", "salt");
        $this->assertInstanceOf(Checkout::class, $checkout);
    }
}
