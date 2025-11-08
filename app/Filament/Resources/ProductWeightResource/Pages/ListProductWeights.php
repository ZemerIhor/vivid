<?php

namespace App\Filament\Resources\ProductWeightResource\Pages;

use App\Filament\Resources\ProductWeightResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductWeights extends ListRecords
{
    protected static string $resource = ProductWeightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
