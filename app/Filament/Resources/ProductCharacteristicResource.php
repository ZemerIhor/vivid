<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCharacteristicResource\Pages;
use App\Filament\Resources\ProductCharacteristicResource\RelationManagers;
use App\Models\ProductCharacteristic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCharacteristicResource extends Resource
{
    protected static ?string $model = ProductCharacteristic::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    
    protected static ?string $navigationGroup = 'Catalog';
    
    protected static ?string $navigationLabel = 'Product Characteristics';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'attribute_data->name->value->en')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->translateAttribute('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Product')
                    ->columnSpanFull()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('characteristics', [])),
                    
                Forms\Components\Repeater::make('characteristics')
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                        Forms\Components\Tabs::make('Translations')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('EN')
                                    ->schema([
                                        Forms\Components\TextInput::make('name_en')
                                            ->label('Name (EN)')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('standard_en')
                                            ->label('Standard (EN)')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('actual_en')
                                            ->label('Actual (EN)')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Tabs\Tab::make('PL')
                                    ->schema([
                                        Forms\Components\TextInput::make('name_pl')
                                            ->label('Name (PL)')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('standard_pl')
                                            ->label('Standard (PL)')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('actual_pl')
                                            ->label('Actual (PL)')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                            ])
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name_en'] ?? null)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->addActionLabel('Add Characteristic')
                    ->deleteAction(
                        fn (Forms\Components\Actions\Action $action) => $action
                            ->requiresConfirmation()
                    ),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.attribute_data.name.value.en')
                    ->label('Product')
                    ->formatStateUsing(fn ($record) => $record->product->translateAttribute('name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name.en')
                    ->label('Name (EN)')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('standard.en')
                    ->label('Standard')
                    ->limit(20),
                Tables\Columns\TextColumn::make('actual.en')
                    ->label('Actual')
                    ->limit(20),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('product_id')
                    ->relationship('product', 'attribute_data->name->value->en')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->translateAttribute('name'))
                    ->searchable()
                    ->preload()
                    ->label('Product'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCharacteristics::route('/'),
            'create' => Pages\CreateProductCharacteristic::route('/create'),
            'edit' => Pages\EditProductCharacteristic::route('/{record}/edit'),
        ];
    }
}
