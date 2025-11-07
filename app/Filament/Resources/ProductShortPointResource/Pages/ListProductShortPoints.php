<?php

namespace App\Filament\Resources\ProductShortPointResource\Pages;

use App\Filament\Resources\ProductShortPointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductShortPoints extends ListRecords
{
    protected static string $resource = ProductShortPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
