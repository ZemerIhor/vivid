<?php

namespace App\Filament\Resources\ProductCharacteristicResource\Pages;

use App\Filament\Resources\ProductCharacteristicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductCharacteristics extends ListRecords
{
    protected static string $resource = ProductCharacteristicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
