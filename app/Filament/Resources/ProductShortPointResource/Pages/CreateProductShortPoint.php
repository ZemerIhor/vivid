<?php

namespace App\Filament\Resources\ProductShortPointResource\Pages;

use App\Filament\Resources\ProductShortPointResource;
use App\Models\ProductShortPoint;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductShortPoint extends CreateRecord
{
    protected static string $resource = ProductShortPointResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Если есть repeater данные, обрабатываем их
        if (isset($data['short_specs']) && is_array($data['short_specs'])) {
            $productId = $data['product_id'];
            $sortOrder = 0;
            
            foreach ($data['short_specs'] as $spec) {
                ProductShortPoint::create([
                    'product_id' => $productId,
                    'name' => [
                        'en' => $spec['name_en'],
                        'pl' => $spec['name_pl'],
                    ],
                    'value' => [
                        'en' => $spec['value_en'],
                        'pl' => $spec['value_pl'],
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
            return new ProductShortPoint();
        }
        
        return static::getModel()::create($data);
    }
}
