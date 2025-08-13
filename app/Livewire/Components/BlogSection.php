<?php

namespace App\Livewire\Components;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogSection extends Component
{
    use WithPagination;

    public $perPage = 6;
    public $sort = 'date_desc';
    public $view = 'grid';
    public $categories = [];
    public $dateFilter = '';

    public function setView($view)
    {
        $this->view = $view;
    }

    public function removeCategory($categoryId)
    {
        $this->categories = array_diff($this->categories, [$categoryId]);
        $this->resetPage();
    }

    public function clearDate()
    {
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->categories = [];
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::where('published_at', '<=', now())
            ->where('published', true);

        if (!empty($this->categories)) {
            $query->whereIn('category_id', $this->categories);
        }

        if ($this->dateFilter) {
            $date = now();
            if ($this->dateFilter === 'week') {
                $query->where('published_at', '>=', $date->subWeek());
            } elseif ($this->dateFilter === 'month') {
                $query->where('published_at', '>=', $date->subMonth());
            } elseif ($this->dateFilter === 'year') {
                $query->where('published_at', '>=', $date->subYear());
            }
        }

        if ($this->sort === 'title_asc') {
            $query->orderByTranslation('title', 'asc');
        } elseif ($this->sort === 'title_desc') {
            $query->orderByTranslation('title', 'desc');
        } elseif ($this->sort === 'date_asc') {
            $query->orderBy('published_at', 'asc');
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $posts = $query->paginate($this->perPage);


        return view('livewire.components.blog-section', [
            'posts' => $posts,
        ]);
    }
}
