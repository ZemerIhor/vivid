<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackFormSubmitted;
use App\Settings\GlobalSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FeedbackFormBlock extends Component
{
    public $isOpen = false;
    public $state = 'form';

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|regex:/^\+?\d{10,15}$/')]
    public $phone = '';

    #[Rule('required|string|max:1000')]
    public $comment = '';

    protected $settingsCache = null;

    public function mount()
    {
        $this->isOpen = false;
        $this->state = 'form';
        $this->resetForm();
        Log::info('FeedbackFormBlock mounted', ['isOpen' => $this->isOpen, 'state' => $this->state]);
    }

    #[On('openFeedbackForm')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->state = 'form';
        $this->resetForm();
        Log::info('Feedback form opened');
    }

    public function closeModal()
    {
        Log::info('Feedback form closed', [
            'formData' => [
                'name' => $this->name,
                'phone' => $this->phone,
                'comment' => $this->comment,
            ]
        ]);
        $this->isOpen = false;
        $this->state = 'form';
        $this->resetForm();
        $this->dispatch('closeFeedbackForm');
    }

    public function submit()
    {
        Log::info('FeedbackForm submit triggered', [
            'formData' => [
                'name' => $this->name,
                'phone' => $this->phone,
                'comment' => $this->comment,
            ]
        ]);

        $validated = $this->validate();
        Log::info('Validated data', ['validated' => $validated]);

        try {
            $settings = $this->getSettings();
            $recipient = $settings['contact_email'] ?? config('mail.feedback_recipient', 'office@landgrou.com');
            Log::info('Sending email to', ['recipient' => $recipient]);

            Mail::to($recipient)->send(new FeedbackFormSubmitted($validated));

            $this->state = 'success';
            $this->resetForm();
            session()->flash('message', __('messages.feedback_form.submitted'));
            $this->dispatch('formSubmitted');
            Log::info('Feedback form submitted successfully', ['validated' => $validated]);
        } catch (\Exception $e) {
            Log::error('Feedback form submission failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            $this->state = 'error';
            $this->dispatch('formSubmissionFailed');
        }
    }

    public function tryAgain()
    {
        $this->state = 'form';
        $this->resetErrorBag();
        Log::info('Feedback form try again');
    }

    public function continueFromSuccess()
    {
        $this->state = 'form';
        $this->resetForm();
        Log::info('Feedback form continued from success');
    }

    private function resetForm()
    {
        $this->reset(['name', 'phone', 'comment']);
        $this->resetErrorBag();
        Log::info('Feedback form reset');
    }

    private function getSettings()
    {
        if ($this->settingsCache === null) {
            $settings = app(GlobalSettings::class);
            $locale = app()->getLocale();
            $this->settingsCache = [
                'feedback_form_title' => $settings->feedback_form_title[$locale] ?? __('messages.feedback_form.title'),
                'feedback_form_description' => $settings->feedback_form_description[$locale] ?? __('messages.feedback_form.description'),
                'feedback_form_image' => $settings->feedback_form_image ? Storage::url($settings->feedback_form_image) : null,
                'contact_email' => $settings->contact_email,
            ];
            Log::info('Global Settings loaded', $this->settingsCache);
        }
        return $this->settingsCache;
    }

    public function render()
    {
        return view('livewire.components.feedback-form-block', [
            'settings' => $this->getSettings(),
        ]);
    }
}
