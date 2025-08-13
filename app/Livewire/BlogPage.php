<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
{
    use WithPagination;

    public $selectedCategory = 'blog'; // Default to 'blog' for tab navigation
    public $view = 'grid'; // Default view mode (grid or list)
    public $categories = []; // Selected category IDs for filtering



    public function render()
    {
        $query = BlogPost::query()
            ->where('published', true)
            ->whereNotNull('published_at');

        // Apply category filter if categories are selected
        if (!empty($this->categories)) {
            $query->whereIn('category_id', $this->categories);
        }

        $posts = $query->orderBy('published_at', 'desc')
            ->paginate(12); // 12 posts to fill three rows of 4

        return view('livewire.blog-page', [
            'posts' => $posts,
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
}
