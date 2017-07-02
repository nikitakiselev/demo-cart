<?php

namespace Nikitakiselev\DemoCart\Coupons;

use Nikitakiselev\DemoCart\Cart;

interface DiscountCoupon
{
    /**
     * Get coupon discount amount
     *
     * @param \Nikitakiselev\DemoCart\Cart $cart
     *
     * @return float
     */
    public function getDiscountAmount(Cart $cart): float;

    /**
     * Get the coupon number
     *
     * @return mixed
     */
    public function getNumber();
}
