<?php

use PHPUnit\Framework\TestCase;
use Nikitakiselev\DemoCart\CartItem;

class CartItemTest extends TestCase
{
    /** @test */
    function it_generate_a_unique_item_id()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);

        $this->assertNotNull($item->getId());
    }

    /** @test */
    function it_can_get_title()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);

        $this->assertEquals('Cart Item', $item->getTitle());
    }

    /** @test */
    function it_can_get_price()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);

        $this->assertEquals(1000, $item->getPrice());
    }

    /** @test */
    function it_can_get_equals()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);

        $this->assertEquals(1, $item->getQuantity());
    }

    /** @test */
    function it_can_get_total_price()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 5,
        ]);

        $this->assertEquals(5000, $item->getTotalPrice());
    }

    /** @test */
    function it_can_change_quantity()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);

       $item->setQuantity(5);

       $this->assertEquals(5, $item->getQuantity());
    }

    /** @test */
    function it_can_increase_quantity()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 5,
        ]);

        $item->increaseQuantity();

        $this->assertEquals(6, $item->getQuantity());
    }

    /** @test */
    function it_can_decrease_quantity()
    {
        $item = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 5,
        ]);

        $item->decreaseQuantity();

        $this->assertEquals(4, $item->getQuantity());
    }
}
