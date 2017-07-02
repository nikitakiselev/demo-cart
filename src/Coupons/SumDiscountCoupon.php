<?php

namespace Nikitakiselev\DemoCart\Coupons;

use Nikitakiselev\DemoCart\Cart;

class SumDiscountCoupon implements DiscountCoupon
{
    /**
     * @var
     */
    protected $amount;
    /**
     * @var string
     */
    protected $number;

    public function __construct(string $number, float $amount)
    {
        $this->amount = $amount;
        $this->number = $number;
    }

    /**
     * Get coupon discount amount.
     *
     * @return float
     */
    public function getDiscountAmount(Cart $cart): float
    {
        return $this->amount;
    }

    /**
     * Get the coupon number.
     *
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }
}
