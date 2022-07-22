<?php

declare(strict_types=1);

namespace Tests\Feature;

use DI\Container;
use Library\Cart;
use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    protected Container $container;

    public function setUp(): void
    {
        $this->container = require __DIR__ . '../../bootstrap.php';

        parent::setUp();
    }

    public function test_add_item_to_basket(): void
    {
        $cart = new Cart();
        $item = new Item();

        $cart->addItem($item);

        $this->assertCount(1, count($cart->getItems()));
    }
}