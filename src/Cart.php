<?php

namespace Nikitakiselev\DemoCart;

use Illuminate\Contracts\Session\Session;
use Nikitakiselev\DemoCart\Storage\CartStorage;
use Nikitakiselev\DemoCart\Coupons\DiscountCoupon;
use Nikitakiselev\DemoCart\Exceptions\CouponExistsException;
use Nikitakiselev\DemoCart\Exceptions\CartItemNotFoundException;

class Cart
{
    /**
     * @var \Nikitakiselev\DemoCart\Storage\CartStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $coupons = [];

    /**
     * @var string
     */
    protected $cartId;

    /**
     * @param Session $session
     * @param \Nikitakiselev\DemoCart\Storage\CartStorage $storage
     * @internal param string $cartId
     */
    public function __construct($session, CartStorage $storage)
    {
        $this->session = $session;
        $this->storage = $storage;

        if ($cartId = $this->session->get('cart_id')) {
            $this->cartId = $cartId;
        } else {
            $this->cartId = uniqid('cart_');
            $this->session->put('cart_id', $this->cartId);
            $this->session->save();
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add new item to the cart.
     *
     * @param CartItem $item
     *
     * @return CartItem
     */
    public function addItem(CartItem $item): CartItem
    {
        $this->items[$item->getId()] = $item;

        return $item;
    }

    /**
     * @param string $id
     * @return CartItem
     * @throws CartItemNotFoundException
     */
    public function getItem(string $id): CartItem
    {
        if ($this->has($id)) {
            return $this->items[$id];
        }

        throw new CartItemNotFoundException("Item with id {$id} not found in the cart");
    }

    /**
     * Check for existing item with id in the cart.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->items[$id]);
    }

    /**
     * Remove item from the cart.
     *
     * @param $id
     *
     * @throws CartItemNotFoundException
     */
    public function removeItem($id)
    {
        if (! $this->has($id)) {
            throw new CartItemNotFoundException("The item with id: {$id} not found in the cart");
        }

        unset($this->items[$id]);
    }

    /**
     * Clear shopping cart.
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return count($this->items);
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $itemsTotal = $this->getItemsTotalPrice();
        $discountTotal = $this->getTotalDiscountAmount();

        return $itemsTotal - $discountTotal;
    }

    /**
     * Add new discount coupon.
     *
     * @param DiscountCoupon $coupon
     *
     * @return DiscountCoupon
     *
     * @throws CouponExistsException
     */
    public function addCoupon(DiscountCoupon $coupon): DiscountCoupon
    {
        if (isset($this->coupons[$coupon->getNumber()])) {
            throw new CouponExistsException(
                "Coupon with number {$coupon->getNumber()} already added to the cart"
            );
        }

        $this->coupons[$coupon->getNumber()] = $coupon;

        return $coupon;
    }

    /**
     * Get all coupons in the cart.
     *
     * @return array
     */
    public function getAllCoupons(): array
    {
        return $this->coupons;
    }

    /**
     * Load cart data from storage.
     *
     * @return bool
     */
    public function load()
    {
        try {
            list($items, $coupons) = $this->storage->get($this->getId());
            $this->items = $items;
            $this->coupons = $coupons;
        } catch (\Exception $exception) {
        }

        return true;
    }

    /**
     * Save cart data to storage.
     *
     * @return mixed
     */
    public function save()
    {
        return $this->storage->store(
            $this->getId(),
            [$this->items, $this->coupons]
        );
    }

    /**
     * @return float
     */
    public function getItemsTotalPrice(): float
    {
        $total = 0;

        /** @var CartItem $item */
        foreach ($this->items as $item) {
            $total += $item->getTotalPrice();
        }

        return $total;
    }

    /**
     * Get total discount amount.
     *
     * @return float
     */
    public function getTotalDiscountAmount(): float
    {
        $total = 0;

        /** @var DiscountCoupon $coupon */
        foreach ($this->coupons as $coupon) {
            $total += $coupon->getDiscountAmount($this);
        }

        return $total;
    }

    /**
     * Get unique cart id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->cartId;
    }

    /**
     * Get all items quantity total.
     *
     * @return int|mixed
     */
    public function getItemsQuantity()
    {
        $quantity = 0;

        /** @var CartItem $item */
        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }
}
