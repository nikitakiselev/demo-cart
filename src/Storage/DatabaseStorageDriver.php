<?php

namespace Nikitakiselev\DemoCart\Storage;

use Nikitakiselev\DemoCart\Models\Cart;

class DatabaseStorageDriver implements CartStorage
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * EqlouentCartStorage constructor.
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function store(string $key, array $data)
    {
        if ($cart = $this->cart->find($key)) {
            return $cart->update([
                'data' => serialize($data),
            ]);
        }

        return (bool) $this->cart->create([
            'id' => $key,
            'data' => serialize($data),
        ]);
    }

    public function get(string $key): array
    {
        if ($cart = $this->cart->find($key)) {
            return unserialize($cart->data);
        }

        return [];
    }

    public function forget(string $key)
    {
        if ($cart = $this->cart->find($key)) {
            return $cart->delete();
        }
    }
}
