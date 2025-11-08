<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Lunar\Admin\Filament\Resources\ProductResource as LunarProductResource;

class ProductResource extends LunarProductResource
{
    protected static function getMainFormComponents(): array
    {
        $components = parent::getMainFormComponents();
        
        // Добавляем наши поля после существующих
        $components[] = Select::make('peat_type_id')
            ->label('Peat Type')
            ->relationship('peatType', 'id')
            ->getOptionLabelFromRecordUsing(fn ($record) => $record ? $record->translate() : '')
            ->searchable()
            ->preload()
            ->nullable();
            
        $components[] = Select::make('product_weight_id')
            ->label('Product Weight')
            ->relationship('productWeight', 'id')
            ->getOptionLabelFromRecordUsing(fn ($record) => $record ? $record->translate() : '')
            ->searchable()
            ->preload()
            ->nullable();
        
        return $components;
    }
}
