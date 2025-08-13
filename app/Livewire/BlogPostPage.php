<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Repositories\BlogPostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BlogPostPage extends Component
{
    public BlogPost $post;
    public $relatedPosts = [];
    private BlogPostRepositoryInterface $blogPostRepository;

    public function boot(BlogPostRepositoryInterface $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function mount($slug)
    {
        try {
            $this->post = $this->blogPostRepository->findBySlug($slug);
            
            if (!$this->post) {
                Log::warning('Blog post not found', ['slug' => $slug]);
                abort(404, 'Blog post not found');
            }

            // Логируем просмотр поста
            Log::info('Blog post viewed', [
                'post_id' => $this->post->id,
                'slug' => $slug,
                'title' => $this->post->title[app()->getLocale()] ?? 'No title',
                'user_ip' => request()->ip(),
            ]);

        } catch (ModelNotFoundException $e) {
            Log::warning('Blog post not found', ['slug' => $slug]);
            abort(404, 'Blog post not found');
        } catch (\Exception $e) {
            Log::error('Error loading blog post', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);
            abort(500, 'Error loading blog post');
        }
    }

    /**
     * Получить похожие посты
     */
    public function getRelatedPostsProperty()
    {
        if (empty($this->relatedPosts)) {
            $this->relatedPosts = $this->blogPostRepository->getRecent(4)
                ->filter(function ($post) {
                    return $post->id !== $this->post->id;
                })
                ->take(3);
        }
        
        return $this->relatedPosts;
    }

    /**
     * Получить следующий пост
     */
    public function getNextPostProperty()
    {
        return $this->blogPostRepository->getPublished()
            ->where('published_at', '>', $this->post->published_at)
            ->sortBy('published_at')
            ->first();
    }

    /**
     * Получить предыдущий пост
     */
    public function getPreviousPostProperty()
    {
        return $this->blogPostRepository->getPublished()
            ->where('published_at', '<', $this->post->published_at)
            ->sortByDesc('published_at')
            ->first();
    }

    /**
     * Получить время чтения (приблизительно)
     */
    public function getReadingTimeProperty()
    {
        $locale = app()->getLocale();
        $content = $this->post->content[$locale] ?? '';
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // Примерно 200 слов в минуту
        
        return max(1, $readingTime); // Минимум 1 минута
    }

    public function render()
    {
        return view('livewire.blog-post-page', [
            'post' => $this->post,
            'relatedPosts' => $this->relatedPosts,
            'nextPost' => $this->nextPost,
            'previousPost' => $this->previousPost,
            'readingTime' => $this->readingTime,
        ]);
    }
}
