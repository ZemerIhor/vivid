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
                ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), [], $locale))
                ->locales(['en', 'pl']),
            TextInput::make('slug')
                ->label('Слаг (URL)')
                ->required()
                ->unique(ignoreRecord: true),
            FileUpload::make('banner')
                ->label('Банер')
                ->image()
                ->disk('public'),
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
                TextColumn::make('title')
                    ->label('Назва')
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->limit(40),
                IconColumn::make('published')
                    ->boolean()
                    ->label('Опубл.'),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->label('Дата публікації'),
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
                        // Ensure unique slug
                        $newRecord->slug = $newRecord->slug . '-' . uniqid();
                        $newRecord->save();

                        // Copy translations
                        foreach ($record->getTranslations() as $attribute => $translations) {
                            foreach ($translations as $locale => $value) {
                                $newRecord->setTranslation($attribute, $locale, $value);
                            }
                        }
                        $newRecord->save();

                        Notification::make()
                            ->title('Пост успішно дубльовано')
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
