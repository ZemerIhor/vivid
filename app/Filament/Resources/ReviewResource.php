<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\Select;
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

    protected static ?string $navigationLabel = 'Відгуки';
    
    protected static ?string $modelLabel = 'Відгук';
    
    protected static ?string $pluralModelLabel = 'Відгуки';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Ім\'я автора')
                ->required()
                ->maxLength(255)
                ->helperText('Ім\'я особи, яка залишила відгук'),
            Select::make('rating')
                ->label('Рейтинг')
                ->options([
                    1 => '⭐ 1 зірка',
                    2 => '⭐⭐ 2 зірки',
                    3 => '⭐⭐⭐ 3 зірки',
                    4 => '⭐⭐⭐⭐ 4 зірки',
                    5 => '⭐⭐⭐⭐⭐ 5 зірок',
                ])
                ->required()
                ->helperText('Оцінка від 1 до 5 зірок'),
            Translate::make()
                ->columnSpanFull()
                ->schema([
                    Textarea::make('comment')
                        ->label('Текст відгуку')
                        ->required()
                        ->rows(4),
                ])
                ->fieldTranslatableLabel(fn ($field, $locale) => $field->getLabel() . ' (' . strtoupper($locale) . ')')
                ->locales(['en', 'pl']),
            Toggle::make('published')
                ->label('Опублікований')
                ->helperText('Чи видимий відгук на сайті'),
            DateTimePicker::make('published_at')
                ->label('Дата публікації')
                ->helperText('Коли відгук буде опублікований'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ім\'я')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Рейтинг')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Відгук')
                    ->getStateUsing(fn ($record) => $record->getTranslation('comment', 'en') ?? $record->getTranslation('comment', 'pl') ?? 'Без тексту')
                    ->limit(50)
                    ->wrap(),
                IconColumn::make('published')
                    ->label('Опублікований')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Дата публікації')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('Не опубліковано'),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('published')
                    ->label('Статус публікації')
                    ->placeholder('Всі відгуки')
                    ->trueLabel('Опубліковані')
                    ->falseLabel('Неопубліковані'),
                \Filament\Tables\Filters\SelectFilter::make('rating')
                    ->label('Рейтинг')
                    ->options([
                        1 => '⭐ 1 зірка',
                        2 => '⭐⭐ 2 зірки',
                        3 => '⭐⭐⭐ 3 зірки',
                        4 => '⭐⭐⭐⭐ 4 зірки',
                        5 => '⭐⭐⭐⭐⭐ 5 зірок',
                    ]),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make()
                    ->label('Переглянути'),
                \Filament\Tables\Actions\EditAction::make()
                    ->label('Редагувати'),
                Action::make('toggle_published')
                    ->label(fn ($record) => $record->published ? 'Приховати' : 'Опублікувати')
                    ->icon(fn ($record) => $record->published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->published ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->published = !$record->published;
                        $record->published_at = $record->published ? now() : null;
                        $record->save();

                        Notification::make()
                            ->title($record->published ? 'Відгук опубліковано' : 'Відгук приховано')
                            ->success()
                            ->send();
                    }),
                Action::make('duplicate')
                    ->label('Дублювати')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->published = false;
                        $newRecord->published_at = null;
                        $newRecord->name = $newRecord->name . ' (копія)';
                        $newRecord->save();

                        // Copy translations
                        foreach (['en', 'pl'] as $locale) {
                            $value = $record->getTranslation('comment', $locale);
                            if ($value) {
                                $newRecord->setTranslation('comment', $locale, $value);
                            }
                        }
                        $newRecord->save();

                        Notification::make()
                            ->title('Відгук успішно дубльовано')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Дублювати відгук')
                    ->modalDescription('Ви впевнені, що хочете створити копію цього відгуку?')
                    ->modalSubmitActionLabel('Так, дублювати')
                    ->color('warning'),
                \Filament\Tables\Actions\DeleteAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkAction::make('publish')
                    ->label('Опублікувати вибрані')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->published = true;
                            $record->published_at = now();
                            $record->save();
                        });

                        Notification::make()
                            ->title('Відгуки опубліковано')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                \Filament\Tables\Actions\BulkAction::make('unpublish')
                    ->label('Приховати вибрані')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->published = false;
                            $record->published_at = null;
                            $record->save();
                        });

                        Notification::make()
                            ->title('Відгуки приховано')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                \Filament\Tables\Actions\DeleteBulkAction::make()
                    ->label('Видалити вибрані'),
            ])
            ->defaultSort('created_at', 'desc');
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
