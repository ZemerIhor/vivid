<?php

namespace App\Filament\Resources\ProductCharacteristicResource\Pages;

use App\Filament\Resources\ProductCharacteristicResource;
use App\Models\ProductCharacteristic;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCharacteristic extends CreateRecord
{
    protected static string $resource = ProductCharacteristicResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Если есть repeater данные, обрабатываем их
        if (isset($data['characteristics']) && is_array($data['characteristics'])) {
            $productId = $data['product_id'];
            $sortOrder = 0;
            
            foreach ($data['characteristics'] as $characteristic) {
                ProductCharacteristic::create([
                    'product_id' => $productId,
                    'name' => [
                        'en' => $characteristic['name_en'],
                        'pl' => $characteristic['name_pl'],
                    ],
                    'standard' => [
                        'en' => $characteristic['standard_en'] ?? '',
                        'pl' => $characteristic['standard_pl'] ?? '',
                    ],
                    'actual' => [
                        'en' => $characteristic['actual_en'],
                        'pl' => $characteristic['actual_pl'],
                    ],
                    'sort_order' => $sortOrder++,
                ]);
            }
            
            // Возвращаем пустой массив чтобы не создавать основную запись
            return [];
        }
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Редирект на список после создания
        $this->redirect(static::getResource()::getUrl('index'));
    }
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Если данные пустые (уже обработаны в mutate), создаем пустую запись
        // которая не будет сохранена
        if (empty($data)) {
            return new ProductCharacteristic();
        }
        
        return static::getModel()::create($data);
    }
}
