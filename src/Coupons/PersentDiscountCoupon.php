<?php

namespace Nikitakiselev\DemoCart\Coupons;

use Nikitakiselev\DemoCart\Cart;

class PersentDiscountCoupon implements DiscountCoupon
{
    /**
     * @var string
     */
    protected $number;

    /**
     * @var int
     */
    protected $persent;

    public function __construct(string $number, int $persent)
    {
        $this->number = $number;
        $this->persent = $persent;
    }

    /**
     * Get coupon discount amount
     *
     * @param Cart $cart
     *
     * @return float
     */
    public function getDiscountAmount(Cart $cart): float
    {
        return $cart->getItemsTotalPrice() * $this->persent / 100;
    }

    /**
     * Get the coupon number
     *
     * @return mixed
     */
    public function getNumber()
    {
        // TODO: Implement getNumber() method.
    }
}
