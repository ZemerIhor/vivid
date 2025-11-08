<?php

namespace App\Filament\Resources\PeatTypeResource\Pages;

use App\Filament\Resources\PeatTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeatType extends EditRecord
{
    protected static string $resource = PeatTypeResource::class;

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
            // Снимаем этот тип со всех продуктов
            \Lunar\Models\Product::where('peat_type_id', $record->id)
                ->update(['peat_type_id' => null]);
            
            // Назначаем выбранным
            if (!empty($data['product_ids'])) {
                \Lunar\Models\Product::whereIn('id', $data['product_ids'])
                    ->update(['peat_type_id' => $record->id]);
            }
        }
    }
}
