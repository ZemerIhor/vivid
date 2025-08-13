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
                        ->label('Відгук')
                        ->required(),
                ])
                ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), [], $locale))
                ->locales(['en', 'pl']),
            TextInput::make('name')
                ->label('Ім’я')
                ->required(),
            Select::make('rating')
                ->label('Рейтинг')
                ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5])
                ->required(),
            Toggle::make('published')
                ->label('Опубліковано'),
            DateTimePicker::make('published_at')
                ->label('Дата публікації'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ім’я'),
                TextColumn::make('rating')
                    ->label('Рейтинг'),
                TextColumn::make('comment')
                    ->label('Відгук')
                    ->getStateUsing(fn ($record) => $record->getTranslation('comment', app()->getLocale()))
                    ->limit(50),
                IconColumn::make('published')
                    ->label('Опубліковано')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Дата публікації')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->locale('uk')->translatedFormat('d F Y') : 'Не опубліковано'),
            ])
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                Action::make('duplicate')
                    ->label('Дублювати')
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
                            ->title('Відгук успішно дубльовано')
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
