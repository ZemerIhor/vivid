<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\Select; // Correct import for Select
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Filament\Notifications\Notification;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Translate::make()
                ->columnSpanFull()
                ->schema([
                    Textarea::make('comment')
                        ->label('Отзыв')
                        ->required(),
                ])
                ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), [], $locale))
                ->locales(['en', 'uk']),
            TextInput::make('name')
                ->label('Имя')
                ->required(),
            Select::make('rating')
                ->label('Рейтинг')
                ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5])
                ->required(),
            Toggle::make('published')
                ->label('Опубликовано'),
            DateTimePicker::make('published_at')
                ->label('Дата публикации'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя'),
                TextColumn::make('rating')
                    ->label('Рейтинг'),
                TextColumn::make('comment')
                    ->label('Отзыв')
                    ->getStateUsing(fn ($record) => $record->getTranslation('comment', app()->getLocale()))
                    ->limit(50),
                IconColumn::make('published')
                    ->label('Опубликовано')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Дата публикации')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->locale(app()->getLocale())->translatedFormat('d F Y') : 'Не опубликовано'),
            ])
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                Action::make('duplicate')
                    ->label('Дублировать')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->published = false;
                        $newRecord->published_at = null;
                        $newRecord->save();

                        // Copy translations
                        foreach ($record->getTranslations() as $attribute => $translations) {
                            foreach ($translations as $locale => $value) {
                                $newRecord->setTranslation($attribute, $locale, $value);
                            }
                        }
                        $newRecord->save();

                        Notification::make()
                            ->title('Отзыв успешно дублирован')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('warning'),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
