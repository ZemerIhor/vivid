<?php

namespace App\Filament\Resources\PeatTypeResource\Pages;

use App\Filament\Resources\PeatTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeatTypes extends ListRecords
{
    protected static string $resource = PeatTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
