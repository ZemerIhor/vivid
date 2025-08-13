<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BlogPost;

class BlogPostPage extends Component
{
    public BlogPost $post;

    public function mount($slug)
    {
        $this->post = BlogPost::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.blog-post-page', [
            'post' => $this->post,
        ]);
    }
}
