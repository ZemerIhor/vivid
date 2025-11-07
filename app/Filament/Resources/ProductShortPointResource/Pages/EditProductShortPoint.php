<?php

namespace App\Filament\Resources\ProductShortPointResource\Pages;

use App\Filament\Resources\ProductShortPointResource;
use App\Models\ProductShortPoint;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductShortPoint extends EditRecord
{
    protected static string $resource = ProductShortPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Загружаем все спецификации этого продукта
        $productId = $this->record->product_id;
        $specs = ProductShortPoint::where('product_id', $productId)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($spec) {
                return [
                    'id' => $spec->id,
                    'name_en' => $spec->name['en'] ?? '',
                    'name_pl' => $spec->name['pl'] ?? '',
                    'value_en' => $spec->value['en'] ?? '',
                    'value_pl' => $spec->value['pl'] ?? '',
                ];
            })
            ->toArray();
        
        $data['product_id'] = $productId;
        $data['short_specs'] = $specs;
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Обрабатываем спецификации
        if (isset($data['short_specs']) && is_array($data['short_specs'])) {
            $productId = $data['product_id'];
            $existingIds = [];
            $sortOrder = 0;
            
            foreach ($data['short_specs'] as $spec) {
                $specData = [
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
                ];
                
                if (isset($spec['id']) && $spec['id']) {
                    // Обновляем существующую
                    ProductShortPoint::where('id', $spec['id'])->update($specData);
                    $existingIds[] = $spec['id'];
                } else {
                    // Создаём новую
                    $newSpec = ProductShortPoint::create($specData);
                    $existingIds[] = $newSpec->id;
                }
            }
            
            // Удаляем те что были убраны из repeater
            ProductShortPoint::where('product_id', $productId)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }
        
        // Возвращаем данные для основной записи (которую не сохраняем)
        return $this->record->toArray();
    }
    
    protected function afterSave(): void
    {
        // Редирект на список
        $this->redirect(static::getResource()::getUrl('index'));
    }
}
