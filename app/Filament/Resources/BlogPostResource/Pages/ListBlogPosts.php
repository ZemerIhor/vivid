<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Створити пост'),
        ];
    }

    public function getTitle(): string
    {
        return 'Блог пости';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // BlogPostResource\Widgets\BlogPostStatsWidget::class,
        ];
    }
}
