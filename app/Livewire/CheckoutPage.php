<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\CartAddress;
use Lunar\Models\Currency;
use Lunar\DataTypes\Price;

class CheckoutPage extends Component
{
    public bool $linesVisible = false;
    public bool $showCityDropdown = true;

    public ?Cart $cart = null;

    public int $currentStep = 1;
    public ?string $chosenShipping = null;
    public ?string $comment = '';

    public array $shippingOptions = [];
    public array $npCities = [];
    public array $npWarehouses = [];
    public array $shippingData = [];

    public string $citySearchTerm = '';
    public bool $privacy_policy = false;

    public array $steps = [
        'personal_info' => 1,
        'delivery' => 2,
    ];

    protected $listeners = [
        'goBackStep' => 'goBackStep',
    ];

    public function mount(): void
    {
        $this->initializeCartAndShipping();
        $this->chosenShipping = $this->cart->meta['shipping_option'] ?? ($this->cart->shippingAddress->meta['shipping_option'] ?? null);
        $this->shippingData = $this->cart->shippingAddress->getAttributes();
        $this->currentStep = session('checkout_step', 1);

        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::info('Инициализация компонента CheckoutPage', [
            'current_step' => $this->currentStep,
            'shipping_initialized' => $this->cart->shippingAddress instanceof \Lunar\Models\CartAddress,
            'shipping_data' => $this->cart->shippingAddress ? $this->cart->shippingAddress->toArray() : null,
            'chosen_shipping' => $this->chosenShipping,
            'cart_meta' => $this->cart->meta ? $this->cart->meta->toArray() : null,
            'cart_lines' => $this->cart->lines->map(fn($line) => [
                'id' => $line->id,
                'subTotal' => $line->subTotal instanceof \Lunar\DataTypes\Price
                    ? ['value' => $line->subTotal->value, 'formatted' => $line->subTotal->formatted()]
                    : $line->subTotal,
            ])->toArray(),
        ]);
    }

    public function hydrate(): void
    {
        $this->initializeCartAndShipping();
        $this->shippingData = $this->cart->shippingAddress->getAttributes();
        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::info('Гидратация компонента CheckoutPage', [
            'current_step' => $this->currentStep,
            'shipping_initialized' => $this->cart->shippingAddress instanceof \Lunar\Models\CartAddress,
            'shipping_data' => $this->cart->shippingAddress ? $this->cart->shippingAddress->toArray() : null,
            'chosen_shipping' => $this->chosenShipping,
            'cart_meta' => $this->cart->meta ? $this->cart->meta->toArray() : null,
        ]);
    }

    protected function initializeCartAndShipping(): void
    {
        $this->cart = CartSession::current();
        if (!$this->cart) {
            Log::error('Корзина не найдена в сессии', [
                'session_id' => session()->getId(),
            ]);
            abort(404, 'Корзина не найдена.');
        }

        $this->cart->load('shippingAddress');
        if (!$this->cart->shippingAddress) {
            try {
                $existingAddress = CartAddress::where('cart_id', $this->cart->id)
                    ->where('type', 'shipping')
                    ->first();

                if (!$existingAddress) {
                    $address = $this->cart->addresses()->create([
                        'type' => 'shipping',
                        'country_id' => 1,
                    ]);
                    Log::info('Создан новый адрес доставки', [
                        'cart_id' => $this->cart->id,
                        'address_id' => $address->id,
                    ]);
                } else {
                    $address = $existingAddress;
                }

                $this->cart->setShippingAddress($address);
            } catch (\Exception $e) {
                Log::error('Ошибка при создании адреса доставки', [
                    'cart_id' => $this->cart->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                abort(500, 'Не удалось инициализировать адрес доставки.');
            }
        }

        if (!$this->cart->shippingAddress instanceof \Lunar\Models\CartAddress) {
            Log::error('Адрес доставки не является валидным экземпляром CartAddress', [
                'cart_id' => $this->cart->id,
                'shipping' => $this->cart->shippingAddress,
            ]);
            abort(500, 'Адрес доставки недействителен.');
        }
    }

    public function rules(): array
    {
        return [
            'shippingData.first_name' => 'required|string|max:255',
            'shippingData.last_name' => 'required|string|max:255',
            'shippingData.contact_phone' => 'required|string|max:30',
            'shippingData.contact_email' => 'required|email|max:255',
            'shippingData.company' => 'nullable|string|max:255',
            'privacy_policy' => 'accepted',
            'shippingData.city' => in_array($this->chosenShipping, ['courier', 'nova-poshta']) ? 'required|string|max:255' : 'nullable|string|max:255',
            'shippingData.line_one' => in_array($this->chosenShipping, ['courier', 'nova-poshta']) ? 'required|string|max:255' : 'nullable|string|max:255',
            'chosenShipping' => 'required|string|in:pickup,nova-poshta,courier',
            'comment' => 'nullable|string|max:500',
        ];
    }

    public function loadShippingOptions(): void
    {
        if (!$this->cart instanceof \Lunar\Models\Cart) {
            Log::error('Корзина недействительна при загрузке вариантов доставки', [
                'cart_id' => $this->cart ? $this->cart->id : null,
            ]);
            return;
        }

        $currency = Currency::where('code', $this->cart->currency->code)->first() ?? Currency::first();

        $this->shippingOptions = ShippingManifest::getOptions($this->cart)->unique('identifier')->map(function ($option) use ($currency) {
            return [
                'name' => $option->name,
                'description' => $option->description,
                'identifier' => $option->identifier,
                'price' => new \Lunar\DataTypes\Price($option->price->value ?? 0, $currency, 1),
                'formatted_price' => (new \Lunar\DataTypes\Price($option->price->value ?? 0, $currency, 1))->formatted(),
                'collect' => $option->collect ?? false,
            ];
        })->toArray();

        Log::info('Опции доставки загружены', [
            'cart_id' => $this->cart->id,
            'options' => array_column($this->shippingOptions, 'identifier'),
        ]);
    }

    public function validateCartPrices(): void
    {
        if (!$this->cart instanceof \Lunar\Models\Cart) {
            Log::error('Корзина недействительна при проверке цен', [
                'cart_id' => $this->cart ? $this->cart->id : null,
            ]);
            return;
        }

        foreach ($this->cart->lines as $line) {
            if ($line->subTotal->value <= 0) {
                Log::warning('Товар в корзине имеет нулевую цену', [
                    'product_id' => $line->purchasable_id,
                    'description' => $line->purchasable->getDescription(),
                    'subtotal' => $line->subTotal->value,
                ]);
                $this->addError('cart', __('messages.checkout.zero_price_error'));
            }
        }
    }

    public function updateLineQuantity($lineId, $quantity): void
    {
        if (!$this->cart instanceof \Lunar\Models\Cart) {
            Log::error('Корзина недействительна при обновлении количества товара', [
                'cart_id' => $this->cart ? $this->cart->id : null,
            ]);
            return;
        }

        if ($quantity < 1) {
            $this->cart->lines()->where('id', $lineId)->delete();
        } else {
            $this->cart->lines()->where('id', $lineId)->update(['quantity' => $quantity]);
        }
        $this->cart->refresh();
        $this->cart->calculate();
    }

    public function saveAddress(): void
    {
        $this->validate([
            'shippingData.first_name' => 'required|string|max:255',
            'shippingData.last_name' => 'required|string|max:255',
            'shippingData.contact_phone' => 'required|string|max:30',
            'shippingData.contact_email' => 'required|email|max:255',
            'shippingData.company' => 'nullable|string|max:255',
            'privacy_policy' => 'accepted',
        ]);

        Log::info('Сохранение адреса доставки', ['shipping' => $this->shippingData]);

        $this->cart->shippingAddress->fill([
            'first_name' => $this->shippingData['first_name'],
            'last_name' => $this->shippingData['last_name'],
            'contact_phone' => $this->shippingData['contact_phone'],
            'contact_email' => $this->shippingData['contact_email'],
            'company' => $this->shippingData['company'] ?? null,
        ])->save();

        $this->cart->setShippingAddress($this->cart->shippingAddress);
        $this->cart->refresh();

        $this->currentStep = $this->steps['delivery'];
        session(['checkout_step' => $this->currentStep]);
    }

    public function saveShippingOption(): void
    {
        $this->validate([
            'chosenShipping' => 'required|string|in:pickup,nova-poshta,courier',
            'shippingData.city' => in_array($this->chosenShipping, ['courier', 'nova-poshta']) ? 'required|string|max:255' : 'nullable|string|max:255',
            'shippingData.line_one' => in_array($this->chosenShipping, ['courier', 'nova-poshta']) ? 'required|string|max:255' : 'nullable|string|max:255',
            'comment' => 'nullable|string|max:500',
        ]);

        Log::info('Выбрана опция доставки', [
            'cart_id' => $this->cart->id,
            'chosen_shipping' => $this->chosenShipping,
            'shipping_data' => $this->shippingData,
        ]);

        if (!$this->chosenShipping) {
            Log::error('Опция доставки не выбрана', [
                'cart_id' => $this->cart->id,
            ]);
            $this->addError('chosenShipping', __('messages.checkout.shipping_option_not_found'));
            return;
        }

        $option = \Lunar\Facades\ShippingManifest::getOptions($this->cart)->first(
            fn($opt) => $opt->getIdentifier() === $this->chosenShipping
        );

        if (!$option) {
            Log::error('Опция доставки не найдена', [
                'cart_id' => $this->cart->id,
                'chosen_shipping' => $this->chosenShipping,
            ]);
            $this->addError('chosenShipping', __('messages.checkout.shipping_option_not_found'));
            return;
        }

        try {
            $this->cart->setShippingOption($option);

            $channel = \Lunar\Models\Channel::where('handle', 'web')->first();
            if (!$channel) {
                Log::error('Канал с handle "web" не найден', [
                    'cart_id' => $this->cart->id,
                    'available_channels' => \Lunar\Models\Channel::all()->toArray(),
                ]);
                throw new \Exception('Канал продаж не настроен. Пожалуйста, обратитесь к администратору.');
            }
            $this->cart->channel_id = $channel->id;
            $this->cart->save();

            $this->cart->meta = array_merge($this->cart->meta ? $this->cart->meta->toArray() : [], [
                'shipping_option' => $this->chosenShipping,
                'payment_option' => 'cash_on_delivery',
                'shipping_total' => $option->price->value ?? 0,
                'shipping_sub_total' => $option->price->value ?? 0,
                'shipping_tax_breakdown' => [],
                'sub_total' => $this->cart->subTotal->value ?? 0,
                'total' => $this->cart->total->value ?? 0,
                'tax_total' => 0,
            ]);
            $this->cart->save();
            $this->cart->refresh();

            Log::info('Опция доставки установлена', [
                'cart_id' => $this->cart->id,
                'shipping_option' => $this->cart->meta['shipping_option'] ?? null,
                'payment_option' => $this->cart->meta['payment_option'] ?? null,
                'channel_id' => $this->cart->channel_id,
                'shipping_option_object' => [
                    'name' => $option->name,
                    'description' => $option->description,
                    'identifier' => $option->identifier,
                    'price' => $option->price->value ?? 0,
                ],
            ]);

            if ($this->chosenShipping === 'pickup') {
                $this->shippingData['city'] = $this->shippingData['city'] ?? 'Не требуется';
                $this->shippingData['line_one'] = $this->shippingData['line_one'] ?? 'Самовывоз';
            }

            $this->cart->shippingAddress->fill([
                'city' => $this->shippingData['city'],
                'line_one' => $this->shippingData['line_one'],
                'meta' => array_merge($this->cart->shippingAddress->meta ? $this->cart->shippingAddress->meta->toArray() : [], [
                    'shipping_option' => $this->chosenShipping,
                ]),
            ])->save();

            $this->cart->setShippingAddress($this->cart->shippingAddress);
            $this->cart->refresh();

            $savedAddress = \Lunar\Models\CartAddress::where('cart_id', $this->cart->id)
                ->where('type', 'shipping')
                ->first();
            Log::info('Адрес доставки сохранен', [
                'cart_id' => $this->cart->id,
                'shipping_option' => $savedAddress->meta['shipping_option'] ?? null,
                'shipping_data' => $savedAddress->toArray(),
            ]);

            $this->cart->calculate();
            $this->loadShippingOptions();
        } catch (\Exception $e) {
            Log::error('Ошибка при сохранении адреса доставки', [
                'cart_id' => $this->cart->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->addError('shipping', 'Не удалось сохранить адрес доставки: ' . $e->getMessage());
            return;
        }

        try {
            if ($this->cart->lines->isEmpty()) {
                Log::error('Попытка создать заказ с пустой корзиной', [
                    'cart_id' => $this->cart->id,
                ]);
                $this->addError('order', 'Корзина пуста. Добавьте товары перед оформлением заказа.');
                return;
            }

            Log::info('Состояние корзины перед созданием заказа', [
                'cart_id' => $this->cart->id,
                'meta' => $this->cart->meta ? $this->cart->meta->toArray() : null,
                'shipping_option' => $this->cart->meta['shipping_option'] ?? null,
                'payment_option' => $this->cart->meta['payment_option'] ?? null,
                'channel_id' => $this->cart->channel_id,
                'shipping_address' => $this->cart->shippingAddress ? $this->cart->shippingAddress->toArray() : null,
            ]);

            $cartData = [
                'cart_id' => $this->cart->id,
                'lines' => $this->cart->lines->toArray(),
                'currency_code' => $this->cart->currency->code ?? 'UAH',
                'meta' => $this->cart->meta ? $this->cart->meta->toArray() : [],
            ];
            $fingerprint = sha1(json_encode($cartData));

            $shippingBreakdownItem = new \stdClass();
            $shippingBreakdownItem->name = $option->name;
            $shippingBreakdownItem->identifier = $option->identifier;
            $shippingBreakdownItem->price = new \Lunar\DataTypes\Price($option->price->value ?? 0, $this->cart->currency, 1);

            $shippingBreakdownItems = collect([$shippingBreakdownItem]);
            $shippingBreakdown = new \Lunar\Base\ValueObjects\Cart\ShippingBreakdown($shippingBreakdownItems);

            $taxBreakdownItems = collect([]);
            $taxBreakdown = new \Lunar\Base\ValueObjects\Cart\TaxBreakdown($taxBreakdownItems);

            $order = \Lunar\Models\Order::create([
                'cart_id' => $this->cart->id,
                'channel_id' => $this->cart->channel_id,
                'status' => 'pending',
                'fingerprint' => $fingerprint,
                'sub_total' => $this->cart->subTotal->value ?? 0,
                'discount_total' => 0,
                'shipping_breakdown' => $shippingBreakdown,
                'shipping_total' => $option->price->value ?? 0,
                'total' => $this->cart->total->value ?? 0,
                'tax_total' => 0,
                'tax_breakdown' => $taxBreakdown,
                'currency_code' => $this->cart->currency->code ?? 'UAH',
                'compare_currency_code' => $this->cart->currency->code ?? 'UAH',
                'exchange_rate' => 1,
                'placed_at' => now(),
                'meta' => $this->cart->meta ? $this->cart->meta->toArray() : [],
                'reference' => 'ORDER-' . $this->cart->id . '-' . now()->format('YmdHis'),
                'user_id' => $this->cart->user_id ?? null,
                'customer_id' => $this->cart->customer_id ?? null,
                'notes' => $this->comment ?? null,
                'customer_reference' => null,
            ]);

            foreach ($this->cart->lines as $cartLine) {
                $purchasable = $cartLine->purchasable;
                $translation = $purchasable->translate('name') ?? $purchasable->name ?? $purchasable->sku ?? 'Product';
                $lineTaxBreakdownItems = collect([]);
                $lineTaxBreakdown = new \Lunar\Base\ValueObjects\Cart\TaxBreakdown($lineTaxBreakdownItems);

                \Lunar\Models\OrderLine::create([
                    'order_id' => $order->id,
                    'purchasable_type' => $cartLine->purchasable_type,
                    'purchasable_id' => $cartLine->purchasable_id,
                    'type' => 'physical',
                    'description' => $translation,
                    'option' => $cartLine->meta['variant'] ?? null,
                    'identifier' => $purchasable->sku ?? 'unknown',
                    'unit_price' => $cartLine->unit_price->value ?? 0,
                    'unit_quantity' => $cartLine->unit_quantity ?? 1,
                    'quantity' => $cartLine->quantity,
                    'sub_total' => $cartLine->subTotal->value ?? 0,
                    'discount_total' => 0,
                    'tax_total' => 0,
                    'tax_breakdown' => $lineTaxBreakdown,
                    'total' => $cartLine->subTotal->value ?? 0,
                    'meta' => $cartLine->meta ? $this->cart->meta->toArray() : [],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $shippingCartAddress = $this->cart->shippingAddress;

            \Lunar\Models\OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'first_name' => $shippingCartAddress->first_name,
                'last_name' => $shippingCartAddress->last_name,
                'company_name' => $shippingCartAddress->company ?? null,
                'line_one' => $shippingCartAddress->line_one,
                'line_two' => $shippingCartAddress->line_two ?? null,
                'line_three' => $shippingCartAddress->line_three ?? null,
                'city' => $shippingCartAddress->city,
                'state' => $shippingCartAddress->state ?? null,
                'country_id' => $shippingCartAddress->country_id,
                'contact_email' => $shippingCartAddress->contact_email,
                'contact_phone' => $shippingCartAddress->contact_phone,
                'meta' => $shippingCartAddress->meta ? $shippingCartAddress->meta->toArray() : null,
            ]);

            \Lunar\Models\OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'billing',
                'first_name' => $shippingCartAddress->first_name,
                'last_name' => $shippingCartAddress->last_name,
                'company_name' => $shippingCartAddress->company ?? null,
                'line_one' => $shippingCartAddress->line_one,
                'line_two' => $shippingCartAddress->line_two ?? null,
                'line_three' => $shippingCartAddress->line_three ?? null,
                'city' => $shippingCartAddress->city,
                'state' => $shippingCartAddress->state ?? null,
                'country_id' => $shippingCartAddress->country_id,
                'contact_email' => $shippingCartAddress->contact_email,
                'contact_phone' => $shippingCartAddress->contact_phone,
                'meta' => $shippingCartAddress->meta ? $shippingCartAddress->meta->toArray() : null,
            ]);

            Log::info('Адреса заказа созданы', [
                'order_id' => $order->id,
                'shipping_address' => [
                    'first_name' => $shippingCartAddress->first_name,
                    'last_name' => $shippingCartAddress->last_name,
                    'contact_phone' => $shippingCartAddress->contact_phone,
                    'contact_email' => $shippingCartAddress->contact_email,
                    'city' => $shippingCartAddress->city,
                    'line_one' => $shippingCartAddress->line_one,
                ],
            ]);

            Log::info('Заказ создан', [
                'order_id' => $order->id,
                'cart_id' => $this->cart->id,
                'channel_id' => $order->channel_id,
                'status' => $order->status,
            ]);

            if ($this->comment) {
                $order->meta = array_merge($this->cart->meta ? $this->cart->meta->toArray() : [], ['comment' => $this->comment]);
                $order->save();
            }

            Log::info('Перенаправление на главную страницу', [
                'order_id' => $order->id,
            ]);
            $this->redirect('/');
        } catch (\Exception $e) {
            Log::error('Ошибка при создании заказа', [
                'cart_id' => $this->cart->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'shipping_option' => $this->cart->meta['shipping_option'] ?? null,
                'payment_option' => $this->cart->meta['payment_option'] ?? null,
                'channel_id' => $this->cart->channel_id,
                'cart_shipping_address' => $this->cart->shippingAddress ? $this->cart->shippingAddress->toArray() : null,
            ]);
            $this->addError('order', 'Не удалось создать заказ: ' . $e->getMessage());
        }
    }

    public function updatedCitySearchTerm($value): void
    {
        $this->showCityDropdown = true;
        $this->shippingData['line_one'] = '';
        $this->npWarehouses = [];

        if (strlen($value) < 2) {
            $this->npCities = [];
            $this->showCityDropdown = false;
            return;
        }

        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY', ''),
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'searchSettlements',
            'methodProperties' => [
                'CityName' => $value,
                'Limit' => '50',
                'Page' => '1',
            ],
        ]);

        Log::info('Запрос к API Новой Почты для городов', [
            'search_term' => $value,
            'response_status' => $response->status(),
            'response_body' => $response->json(),
        ]);

        if ($response->successful() && ($response->json('success') ?? false)) {
            $data = $response->json('data') ?? [];
            $this->npCities = collect($data)->flatMap(fn($item) =>
            isset($item['Addresses']) ? $item['Addresses'] : [$item]
            )->filter(fn($city) => isset($city['MainDescription']))->toArray();
        } else {
            Log::error('Ошибка при получении городов от Новой Почты', [
                'search_term' => $value,
                'response' => $response->json(),
                'errors' => $response->json('errors') ?? 'Нет деталей ошибки',
            ]);
            $this->npCities = [];
            $this->showCityDropdown = false;
            $this->addError('shippingData.city', __('messages.checkout.city_load_error'));
        }

        Log::debug('Обновление citySearchTerm', [
            'citySearchTerm' => $this->citySearchTerm,
            'shippingData' => $this->shippingData,
            'npCities' => $this->npCities,
        ]);
    }

    public function selectCity($cityName): void
    {
        $this->shippingData['city'] = $cityName;
        $this->citySearchTerm = $cityName;
        $this->showCityDropdown = false;
        $this->shippingData['line_one'] = '';
        $this->npWarehouses = [];

        // Сохраняем город в CartAddress
        $this->cart->shippingAddress->fill([
            'city' => $this->shippingData['city'],
        ])->save();
        $this->cart->setShippingAddress($this->cart->shippingAddress);
        $this->cart->refresh();

        $city = collect($this->npCities)->first(fn($c) => ($c['MainDescription'] ?? '') === $cityName);

        if ($city && isset($city['DeliveryCity'])) {
            Log::info('Выбран город', [
                'city' => $cityName,
                'delivery_city' => $city['DeliveryCity'],
                'ref' => $city['Ref'],
                'city_data' => $city,
            ]);
            $this->fetchNovaPoshtaWarehouses($city['DeliveryCity']);
        } else {
            Log::error('DeliveryCity не найден для города', [
                'city' => $cityName,
                'available_cities' => $this->npCities,
            ]);
            $this->addError('shippingData.city', __('messages.checkout.warehouse_load_error'));
        }

        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::debug('После выбора города', [
            'shippingData' => $this->shippingData,
            'cartAddress' => $this->cart->shippingAddress->toArray(),
            'npWarehouses' => $this->npWarehouses,
        ]);
    }

    public function updatedShippingDataCity($cityName): void
    {
        $this->shippingData['line_one'] = '';
        $this->npWarehouses = [];

        // Сохраняем город в CartAddress
        $this->cart->shippingAddress->fill([
            'city' => $cityName,
        ])->save();
        $this->cart->setShippingAddress($this->cart->shippingAddress);
        $this->cart->refresh();

        $city = collect($this->npCities)->first(fn($c) => ($c['MainDescription'] ?? '') === $cityName);

        if ($city && isset($city['DeliveryCity'])) {
            Log::info('Обновлен город через updatedShippingDataCity', [
                'city' => $cityName,
                'delivery_city' => $city['DeliveryCity'],
                'ref' => $city['Ref'],
            ]);
            $this->fetchNovaPoshtaWarehouses($city['DeliveryCity']);
        } else {
            Log::error('DeliveryCity не найден для города в updatedShippingDataCity', [
                'city' => $cityName,
                'available_cities' => $this->npCities,
            ]);
        }

        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::debug('После обновления shippingData.city', [
            'shippingData' => $this->shippingData,
            'cartAddress' => $this->cart->shippingAddress->toArray(),
            'npWarehouses' => $this->npWarehouses,
        ]);
    }

    public function updatedShippingDataLineOne($value): void
    {
        // Сохраняем line_one в CartAddress
        $this->cart->shippingAddress->fill([
            'line_one' => $value,
        ])->save();
        $this->cart->setShippingAddress($this->cart->shippingAddress);
        $this->cart->refresh();

        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::debug('Обновление shippingData.line_one', [
            'line_one' => $value,
            'shippingData' => $this->shippingData,
            'cartAddress' => $this->cart->shippingAddress->toArray(),
            'chosenShipping' => $this->chosenShipping,
        ]);
    }

    public function updatedChosenShipping($value): void
    {
        $this->cart->calculate();
        $this->loadShippingOptions();

        Log::debug('Обновление chosenShipping', [
            'chosenShipping' => $value,
            'shippingData' => $this->shippingData,
            'cartAddress' => $this->cart->shippingAddress->toArray(),
        ]);
    }

    public function fetchNovaPoshtaWarehouses(string $cityRef): void
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY', ''),
            'modelName' => 'Address',
            'calledMethod' => 'getWarehouses',
            'methodProperties' => [
                'CityRef' => $cityRef,
                'TypeOfWarehouseRef' => '841339c7-591a-42e2-8233-7a0a00f0ed6f',
                'Limit' => '100',
                'Page' => '1',
            ],
        ]);

        Log::info('Запрос к API Новой Почты для отделений', [
            'cityRef' => $cityRef,
            'response_status' => $response->status(),
            'response_body' => $response->json(),
            'errors' => $response->json('errors') ?? 'Нет деталей ошибки',
        ]);

        if ($response->successful() && ($response->json('success') ?? false)) {
            $this->npWarehouses = $response->json('data') ?? [];
            if (empty($this->npWarehouses)) {
                Log::warning('Список отделений пуст для города', ['cityRef' => $cityRef]);
                $this->addError('shippingData.line_one', __('messages.checkout.warehouse_empty_error'));
            }
        } else {
            Log::error('Ошибка при получении отделений Новой Почты', [
                'cityRef' => $cityRef,
                'response' => $response->json(),
                'errors' => $response->json('errors') ?? 'Нет деталей ошибки',
            ]);
            $this->npWarehouses = [];
            $this->addError('shippingData.line_one', __('messages.checkout.warehouse_load_error'));
        }

        Log::debug('После загрузки отделений Новой Почты', [
            'npWarehouses' => $this->npWarehouses,
            'shippingData' => $this->shippingData,
            'cartAddress' => $this->cart->shippingAddress->toArray(),
        ]);
    }

    public function getShippingOptionProperty()
    {
        return collect($this->shippingOptions)->firstWhere('identifier', $this->chosenShipping) ?? null;
    }

    public function goBackStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            session(['checkout_step' => $this->currentStep]);
        }
    }

    public function render(): View
    {
        Log::info('Рендеринг CheckoutPage', [
            'current_step' => $this->currentStep,
            'shipping_exists' => $this->cart->shippingAddress instanceof \Lunar\Models\CartAddress,
            'cart_id' => $this->cart ? $this->cart->id : null,
            'chosen_shipping' => $this->chosenShipping,
            'shipping_data' => $this->shippingData,
            'cart_meta' => $this->cart->meta ? $this->cart->meta->toArray() : null,
        ]);

        return view('livewire.checkout-page', [
            'shippingOption' => $this->getShippingOptionProperty(),
            'steps' => $this->steps,
            'shippingOptions' => $this->shippingOptions,
            'npCities' => $this->npCities,
            'npWarehouses' => $this->npWarehouses,
        ])->layout('layouts.checkout');
    }
}
