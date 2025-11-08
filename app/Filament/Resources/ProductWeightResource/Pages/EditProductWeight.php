<?php

namespace App\Filament\Resources\ProductWeightResource\Pages;

use App\Filament\Resources\ProductWeightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductWeight extends EditRecord
{
    protected static string $resource = ProductWeightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        $data = $this->data;
        $record = $this->record;
        
        if (isset($data['product_ids'])) {
            // Снимаем этот вес со всех продуктов
            \Lunar\Models\Product::where('product_weight_id', $record->id)
                ->update(['product_weight_id' => null]);
            
            // Назначаем выбранным
            if (!empty($data['product_ids'])) {
                \Lunar\Models\Product::whereIn('id', $data['product_ids'])
                    ->update(['product_weight_id' => $record->id]);
            }
        }
    }
}
