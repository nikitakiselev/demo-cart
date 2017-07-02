# Simple demo shopping cart

[![Build Status](https://travis-ci.org/nikitakiselev/demo-cart.svg?branch=master)](https://travis-ci.org/nikitakiselev/demo-cart)

Simple shopping cart, developed for demo purposes.

## Usage Examples

```php

$cart = app(\Nikitakiselev\DemoCart\Cart::class);
      
$item = $cart->addItem(new \Nikitakiselev\DemoCart\CartItem([
    'title' => 'cart item',
    'price' => 2000,
    'quantity' => 1.
]));

// save cart's content in the storage
$cart->save();

// change item quantity
$cart->getItem($item->getId())->setQuantity(10);
$cart->getItem($item->getId())->increaseQuantity();
$cart->getItem($item->getId())->decreaseQuantity();
$cart->save();

// get cart total
$cart->getTotal();

// remove item from the cart
$cart->removeItem($item->getId());
$cart->save();

// Add sum discount coupon on the 5000
$sumDiscountCoupon = new \Nikitakiselev\DemoCart\Coupons\SumDiscountCoupon(
    'coupon_number', 5000
);
$cart->addCoupon($sumDiscountCoupon);

// Add persent discount coupon on 10%
$percentDiscountCoupon = new \Nikitakiselev\DemoCart\Coupons\PersentDiscountCoupon(
    'coupon_number_2', 10
);
$cart->addCoupon($percentDiscountCoupon);

$cart->save();

// Get all coupons in the cart
$cart->getAllCoupons();

// Get all items in the cart
$cart->getItems();
```

## Discount coupons

You can add various types of the discount coupons.
Each coupon must implements `Nikitakiselev\DemoCart\Coupons\DiscountCoupon` contract.

For example:

```php
use Nikitakiselev\DemoCart\Cart;

class ProductCountDependDiscount implements \Nikitakiselev\DemoCart\Coupons\DiscountCoupon
{
    /**
     * Get coupon discount amount
     *
     * @param Cart $cart
     *
     * @return float
     */
    public function getDiscountAmount(Cart $cart): float
    {
        $discount = 5;
        $total = $cart->getTotal();
        $productsQuantity = $cart->getItemsQuantity();

        if ($productsQuantity > 5) {
            $discount = 20;
        }

        return $total * $discount / 100;
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
```
