<?php

use PHPUnit\Framework\TestCase;
use Nikitakiselev\DemoCart\Cart;
use Nikitakiselev\DemoCart\CartItem;

class CartTest extends TestCase
{
    protected $cart;

    protected $cartItem;

    /**
     * @var \Mockery\MockInterface
     */
    protected $storage;

    protected $session;

    protected function setUp()
    {
        parent::setUp();

        $this->storage = Mockery::mock('Nikitakiselev\DemoCart\Storage\CartStorage');

        $this->session = Mockery::spy('Illuminate\Session\SessionManager');

        $this->cart = new Cart($this->session, $this->storage);

        $this->cartItem = new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 1,
        ]);
    }

    /** @test */
    function it_can_get_id()
    {
        $this->assertNotEmpty($this->cart->getId());
    }

    /** @test */
    function it_can_get_all_items()
    {
        $this->cart->addItem($this->cartItem);

        $this->assertCount(1, $this->cart->getItems());
    }

    /** @test */
    function it_can_add_cart_item()
    {
        $this->cart->addItem($this->cartItem);

        $this->assertEquals(1, $this->cart->getItemsCount());
    }

    /** @test */
    function it_can_remove_item()
    {
        $item = $this->cart->addItem($this->cartItem);

        $this->cart->removeItem($item->getId());

        $this->assertEquals(0, $this->cart->getItemsCount());
    }

    /** @test */
    function it_can_get_item()
    {
        $this->cart->addItem($this->cartItem);

        $item = $this->cart->getItem($this->cartItem->getId());

        $this->assertEquals($this->cartItem, $item);
    }

    /** @test */
    function it_throws_exception_if_requested_item_not_found()
    {
        $this->expectException('Nikitakiselev\DemoCart\Exceptions\CartItemNotFoundException');

        $this->cart->getItem('non_exists_item_id');
    }

    /** @test */
    function it_thows_exception_if_removing_non_existing_item()
    {
        $nonExistingItemId = 'foobar';

        $this->expectException('Nikitakiselev\DemoCart\Exceptions\CartItemNotFoundException');

        $this->cart->removeItem($nonExistingItemId);
    }

    /** @test */
    function it_can_clear_all_items()
    {
        $this->cart->addItem($this->cartItem);
        $this->cart->addItem($this->cartItem);

        $this->cart->clear();

        $this->assertEquals(0, $this->cart->getItemsCount());
    }

    /** @test */
    function it_can_add_a_discount_coupon()
    {
        $coupon = $this->getCoupon();

        $this->cart->addCoupon($coupon);

        $this->assertCount(1, $this->cart->getAllCoupons());
    }

    /** @test */
    function it_throws_exception_if_coupon_with_same_number_already_added()
    {
        $coupon = $this->getCoupon();

        $this->expectException('Nikitakiselev\DemoCart\Exceptions\CouponExistsException');

        $this->cart->addCoupon($coupon);

        $this->cart->addCoupon($coupon);
    }

    /** @test */
    function it_can_get_all_coupons()
    {
        $this->cart->addCoupon($this->getCoupon('coupon_1'));
        $this->cart->addCoupon($this->getCoupon('coupon_2'));

        $this->assertCount(2, $this->cart->getAllCoupons());
        $this->assertArrayHasKey('coupon_1', $this->cart->getAllCoupons());
        $this->assertArrayHasKey('coupon_2', $this->cart->getAllCoupons());
    }

    /** @test */
    function it_can_get_total_amount_with_items_only()
    {
        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 2,
        ]));

        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 2000,
            'quantity' => 3,
        ]));

        $this->assertEquals(8000, $this->cart->getTotal());
    }

    /** @test */
    function it_can_get_total_amount_with_items_and_coupons()
    {
        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 1000,
            'quantity' => 2,
        ]));

        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 2000,
            'quantity' => 3,
        ]));

        $this->cart->addCoupon($this->getCoupon('coupon_1', 1000));
        $this->cart->addCoupon($this->getCoupon('coupon_2', 1000));

        $this->assertEquals(6000, $this->cart->getTotal());
    }

    /**
     * @param string|null $number
     *
     * @param int $amount
     *
     * @return \Mockery\MockInterface
     */
    private function getCoupon($number = null, $amount = 1000): \Mockery\MockInterface
    {
        $coupon = Mockery::mock('Nikitakiselev\DemoCart\Coupons\DiscountCoupon');

        $coupon->shouldReceive('getNumber')->andReturn($number ?: 'coupon_code');

        $coupon->shouldReceive('getDiscountAmount')->andReturn($amount);

        return $coupon;
    }

    /** @test */
    function it_can_save_data_to_the_storage()
    {
        $this->storage->shouldReceive('store')->andReturn(true);

        $this->assertTrue($this->cart->save());
    }

    /** @test */
    function it_can_load_data_from_the_storage()
    {
        $this->storage->shouldReceive('get')
            ->andReturn([
                ['cart_items'],
                ['cart_coupons'],
            ]);

        $this->cart->load();

        $this->assertEquals(1, $this->cart->getItemsCount());
        $this->assertCount(1, $this->cart->getAllCoupons());
    }

    /** @test */
    function it_can_get_product_quantity()
    {
        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 2000,
            'quantity' => 3,
        ]));

        $this->cart->addItem(new CartItem([
            'title' => 'Cart Item',
            'price' => 2000,
            'quantity' => 2,
        ]));

        $this->assertEquals(5, $this->cart->getItemsQuantity());
    }
}
