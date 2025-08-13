<?php

use Lunar\Actions\Carts\GenerateFingerprint;

return [
    /*
    | Fingerprint Generator
    */
    'fingerprint_generator' => GenerateFingerprint::class,

    /*
    | Authentication policy
    */
    'auth_policy' => 'merge',

    /*
    | Cart Pipelines
    */
    'pipelines' => [
        'cart' => [
            Lunar\Pipelines\Cart\CalculateLines::class,
            Lunar\Pipelines\Cart\ApplyDiscounts::class,
            // CalculateTax отключен, так как налоги не используются
            // Lunar\Pipelines\Cart\CalculateTax::class,
            Lunar\Pipelines\Cart\Calculate::class,
        ],
        'cart_lines' => [
            Lunar\Pipelines\CartLine\GetUnitPrice::class,
        ],
        'order_creation' => [
            Lunar\Pipelines\Order\Creation\CreateOrderLines::class,
            Lunar\Pipelines\Order\Creation\CreateOrderAddresses::class,
        ],
    ],

    /*
    | Cart Actions
    */
    'actions' => [
        'add_to_cart' => Lunar\Actions\Carts\AddOrUpdatePurchasable::class,
        'get_existing_cart_line' => Lunar\Actions\Carts\GetExistingCartLine::class,
        'update_cart_line' => Lunar\Actions\Carts\UpdateCartLine::class,
        'remove_from_cart' => Lunar\Actions\Carts\RemovePurchasable::class,
        'add_address' => Lunar\Actions\Carts\AddAddress::class,
        'set_shipping_option' => Lunar\Actions\Carts\SetShippingOption::class,
        'order_create' => Lunar\Actions\Carts\CreateOrder::class,
    ],

    /*
    | Cart Action Validators
    */
    'validators' => [
        'add_to_cart' => [
            Lunar\Validation\CartLine\CartLineQuantity::class,
            Lunar\Validation\CartLine\CartLineStock::class,
        ],
        'update_cart_line' => [
            Lunar\Validation\CartLine\CartLineQuantity::class,
            Lunar\Validation\CartLine\CartLineStock::class,
        ],
        'remove_from_cart' => [],
        'set_shipping_option' => [],
        'order_create' => [],
    ],

    /*
    | Default eager loading
    */
    'eager_load' => [
        'currency',
        'lines.purchasable.taxClass',
        'lines.purchasable.values',
        'lines.purchasable.product.thumbnail',
        'lines.purchasable.prices.currency',
        'lines.purchasable.prices.priceable',
        'lines.purchasable.product',
        'lines.cart.currency',
    ],

    /*
    | Prune carts
    */
    'prune_tables' => [
        'enabled' => false,
        'pipelines' => [
            Lunar\Pipelines\CartPrune\PruneAfter::class,
            Lunar\Pipelines\CartPrune\WithoutOrders::class,
            Lunar\Pipelines\CartPrune\WhereNotMerged::class,
        ],
        'prune_interval' => 90, // days
    ],
];
