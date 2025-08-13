<?php

namespace App\Livewire;

use App\Repositories\BlogPostRepositoryInterface;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
{
    use WithPagination;

    public $selectedCategory = 'blog'; // Default to 'blog' for tab navigation
    public $view = 'grid'; // Default view mode (grid or list)
    public $categories = []; // Selected category IDs for filtering
    public $searchQuery = ''; // Поисковый запрос
    public $perPage = 12; // Количество постов на страницу

    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository
    ) {
        parent::__construct();
    }

    /**
     * Поиск постов
     */
    public function search()
    {
        $this->resetPage();
    }

    /**
     * Очистка поискового запроса
     */
    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->resetPage();
    }

    /**
     * Получить последние посты
     */
    public function getRecentPostsProperty()
    {
        return $this->blogPostRepository->getRecent(6);
    }

    public function render()
    {
        // Если есть поисковый запрос, используем поиск
        if (!empty(trim($this->searchQuery))) {
            $posts = $this->blogPostRepository->search(trim($this->searchQuery), $this->perPage);
        } else {
            // Обычная пагинация опубликованных постов
            $posts = $this->blogPostRepository->getPublishedPaginated($this->perPage);
        }

        return view('livewire.blog-page', [
            'posts' => $posts,
            'recentPosts' => $this->recentPosts,
            'hasSearch' => !empty(trim($this->searchQuery)),
        ]);
    }

    public function setCategory($category)
    {
        $this->selectedCategory = $category;
        $this->categories = [$category]; // Update categories array for filtering
        $this->resetPage(); // Reset pagination when category changes
    }

    public function removeCategory($categoryId)
    {
        $this->categories = array_diff($this->categories, [$categoryId]);
        $this->resetPage(); // Reset pagination
    }

    public function setView($view)
    {
        $this->view = $view; // Set view mode (grid or list)
    }

    /**
     * Обновление поискового запроса в реальном времени
     */
    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    /**
     * Очистка всех фильтров
     */
    public function clearAllFilters()
    {
        $this->searchQuery = '';
        $this->categories = [];
        $this->selectedCategory = 'blog';
        $this->resetPage();
    }
}
