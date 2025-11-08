<?php

namespace App\Filament\Resources\PeatTypeResource\Pages;

use App\Filament\Resources\PeatTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeatType extends CreateRecord
{
    protected static string $resource = PeatTypeResource::class;
    
    protected function afterCreate(): void
    {
        $data = $this->data;
        $record = $this->record;
        
        if (isset($data['product_ids']) && !empty($data['product_ids'])) {
            \Lunar\Models\Product::whereIn('id', $data['product_ids'])
                ->update(['peat_type_id' => $record->id]);
        }
    }
}
