<?php

use PHPUnit\Framework\TestCase;
use Nikitakiselev\DemoCart\Coupons\PersentDiscountCoupon;

class DiscountCouponTest extends TestCase
{
    /** @test */
    function persent_discount_coupon_return_a_correct_discount_amount()
    {
        $cart = Mockery::mock('Nikitakiselev\DemoCart\Cart')
            ->shouldReceive('getItemsTotalPrice')
            ->andReturn(10000)
            ->getMock();

        $coupon = new PersentDiscountCoupon('number', 20);

        $this->assertEquals(2000, $coupon->getDiscountAmount($cart));
    }
}
