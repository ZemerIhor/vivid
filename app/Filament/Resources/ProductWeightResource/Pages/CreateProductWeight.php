<?php

namespace App\Filament\Resources\ProductWeightResource\Pages;

use App\Filament\Resources\ProductWeightResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductWeight extends CreateRecord
{
    protected static string $resource = ProductWeightResource::class;
    
    protected function afterCreate(): void
    {
        $data = $this->data;
        $record = $this->record;
        
        if (isset($data['product_ids']) && !empty($data['product_ids'])) {
            \Lunar\Models\Product::whereIn('id', $data['product_ids'])
                ->update(['product_weight_id' => $record->id]);
        }
    }
}
