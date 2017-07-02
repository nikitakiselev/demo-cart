<?php

namespace Nikitakiselev\DemoCart;

class CartItem
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * CartItem constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = uniqid();

        $this->title = $data['title'];
        $this->price = $data['price'];
        $this->quantity = $data['quantity'];
    }

    /**
     * Get unique cart item identificator
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getTotalPrice() : float
    {
        return $this->quantity * $this->price;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function increaseQuantity()
    {
        $this->setQuantity($this->quantity + 1);
    }

    public function decreaseQuantity()
    {
        $this->setQuantity($this->quantity - 1);
    }
}
