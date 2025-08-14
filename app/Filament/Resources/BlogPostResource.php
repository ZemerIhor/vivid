<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Filament\Notifications\Notification;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Блог пости';
    
    protected static ?string $modelLabel = 'Блог пост';
    
    protected static ?string $pluralModelLabel = 'Блог пости';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Translate::make()
                ->columnSpanFull()
                ->schema([
                    TextInput::make('title')
                        ->label('Назва')
                        ->required(),
                    Textarea::make('excerpt')
                        ->label('Короткий опис'),
                    RichEditor::make('content')
                        ->label('Контент')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('seo_title')
                        ->label('SEO назва'),
                    Textarea::make('seo_description')
                        ->label('SEO опис'),
                ])
                ->fieldTranslatableLabel(fn ($field, $locale) => $field->getLabel() . ' (' . strtoupper($locale) . ')')
                ->locales(['en', 'pl']),
            TextInput::make('slug')
                ->label('URL-адреса')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Унікальна адреса для посту'),
            FileUpload::make('banner')
                ->label('Банер')
                ->image()
                ->disk('public')
                ->helperText('Зображення для посту'),
            Toggle::make('published')
                ->label('Опубліковано')
                ->helperText('Чи видимий пост на сайті'),
            DateTimePicker::make('published_at')
                ->label('Дата публікації')
                ->helperText('Коли пост буде опублікований'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Назва')
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', 'en') ?? $record->getTranslation('title', 'pl') ?? 'Без назви')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('URL')
                    ->limit(30)
                    ->searchable(),
                IconColumn::make('published')
                    ->boolean()
                    ->label('Опублікований')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime('d.m.Y H:i')
                    ->label('Дата публікації')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i')
                    ->label('Створено')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('published')
                    ->label('Статус публікації')
                    ->placeholder('Всі пости')
                    ->trueLabel('Опубліковані')
                    ->falseLabel('Неопубліковані'),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make()
                    ->label('Переглянути'),
                \Filament\Tables\Actions\EditAction::make()
                    ->label('Редагувати'),
                Action::make('duplicate')
                    ->label('Дублювати')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->published = false;
                        $newRecord->published_at = null;
                        $newRecord->slug = $newRecord->slug . '-копія-' . uniqid();
                        $newRecord->save();

                        // Copy translations
                        foreach (['title', 'excerpt', 'content', 'seo_title', 'seo_description'] as $field) {
                            foreach (['en', 'pl'] as $locale) {
                                $value = $record->getTranslation($field, $locale);
                                if ($value) {
                                    $newRecord->setTranslation($field, $locale, $value);
                                }
                            }
                        }
                        $newRecord->save();

                        Notification::make()
                            ->title('Пост успішно дубльовано')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Дублювати пост')
                    ->modalDescription('Ви впевнені, що хочете створити копію цього поста?')
                    ->modalSubmitActionLabel('Так, дублювати')
                    ->color('warning'),
                \Filament\Tables\Actions\DeleteAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
