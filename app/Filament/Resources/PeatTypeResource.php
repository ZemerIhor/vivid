<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeatTypeResource\Pages;
use App\Filament\Resources\PeatTypeResource\RelationManagers;
use App\Models\PeatType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class PeatTypeResource extends Resource
{
    protected static ?string $model = PeatType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationGroup = 'Catalog';
    
    protected static ?string $navigationLabel = 'Peat Types';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('EN')
                            ->schema([
                                Forms\Components\TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('PL')
                            ->schema([
                                Forms\Components\TextInput::make('name.pl')
                                    ->label('Name (PL)')
                                    ->required(),
                            ]),
                    ])->columnSpanFull(),
                    
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                    
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                    
                Forms\Components\Section::make('Продукты')
                    ->description('Выберите продукты для этого типа торфа')
                    ->schema([
                        Forms\Components\Select::make('product_ids')
                            ->label('Продукты')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return \Lunar\Models\Product::all()->mapWithKeys(function ($product) {
                                    $name = $product->translateAttribute('name') ?? 'Product #' . $product->id;
                                    return [$product->id => $name];
                                });
                            })
                            ->default(function ($record) {
                                if (!$record) return [];
                                return \Lunar\Models\Product::where('peat_type_id', $record->id)->pluck('id')->toArray();
                            }),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name.en')
                    ->label('Name (EN)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name.pl')
                    ->label('Name (PL)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
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
            'index' => Pages\ListPeatTypes::route('/'),
            'create' => Pages\CreatePeatType::route('/create'),
            'edit' => Pages\EditPeatType::route('/{record}/edit'),
        ];
    }
    
    // Hook после сохранения для обновления продуктов
    protected static function afterSave(Model $record, array $data): void
    {
        if (isset($data['product_ids'])) {
            // Снимаем этот тип со всех продуктов, которые его имели
            \Lunar\Models\Product::where('peat_type_id', $record->id)
                ->update(['peat_type_id' => null]);
            
            // Назначаем выбранным продуктам
            if (!empty($data['product_ids'])) {
                \Lunar\Models\Product::whereIn('id', $data['product_ids'])
                    ->update(['peat_type_id' => $record->id]);
            }
        }
    }
}
