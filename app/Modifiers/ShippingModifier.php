<?php

namespace App\Modifiers;

use Closure;
use Illuminate\Support\Facades\Log;
use Lunar\Base\ShippingModifier as BaseShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\TaxClass;

class ShippingModifier extends BaseShippingModifier
{
    public function handle(Cart $cart, Closure $next)
    {
        Log::debug('Обработка ShippingModifier', ['cart_id' => $cart->id]);

        // Проверяем, что валюта установлена
        if (!$cart->currency) {
            Log::error('Валюта корзины не определена', ['cart_id' => $cart->id]);
            return $next($cart);
        }

        // Получаем или создаем класс налогов
        $taxClass = TaxClass::getDefault() ?? TaxClass::firstOrCreate(
            ['name' => 'Default'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Определяем опции доставки
        $newOptions = collect([
            new ShippingOption(
                name: 'Самовивіз з магазину',
                description: 'Ви можете забрати товар самостійно з магазину.',
                identifier: 'pickup',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass,
                collect: true
            ),
            new ShippingOption(
                name: 'Нова Пошта',
                description: 'Доставка на відділення Нової Пошти.',
                identifier: 'nova-poshta',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass
            ),
            new ShippingOption(
                name: 'Кур’єрська доставка',
                description: 'Доставка кур’єром за адресою.',
                identifier: 'courier',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass
            ),
        ]);

        // Добавляем опции
        ShippingManifest::addOptions($newOptions);

        Log::debug('Опции доставки добавлены', [
            'options' => $newOptions->pluck('identifier')->toArray(),
        ]);

        return $next($cart);
    }
}
