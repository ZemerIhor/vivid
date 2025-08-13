<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Log;

class ContactForm extends Component
{
    public $isOpen = false;
    public $state = 'form';

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|regex:/^\+?\d{10,15}$/')]
    public $phone = '';

    #[Rule('required|string|max:1000')]
    public $formMessage = '';

    public function mount()
    {
        $this->isOpen = false;
        $this->state = 'form';
        $this->resetForm();
        Log::info('ContactForm mounted', ['isOpen' => $this->isOpen, 'state' => $this->state]);
    }

    #[On('openContactForm')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->state = 'form';
        $this->resetForm();
        Log::info('ContactForm opened', ['isOpen' => $this->isOpen, 'state' => $this->state]);
    }

    public function resetModal()
    {
        $this->reset(['name', 'phone', 'formMessage']);
        $this->state = 'form';
        $this->isOpen = false;
        $this->resetErrorBag();
        Log::info('ContactForm modal reset', ['state' => $this->state, 'isOpen' => $this->isOpen]);
    }

    public function closeModal()
    {
        Log::info('ContactForm closeModal triggered', [
            'isOpen' => $this->isOpen,
            'state' => $this->state,
            'formData' => [
                'name' => $this->name,
                'phone' => $this->phone,
                'formMessage' => $this->formMessage,
            ]
        ]);

        $this->isOpen = false;
        $this->state = 'form';
        $this->resetForm();
        $this->dispatch('closeContactForm');
    }

    public function submit()
    {
        Log::info('ContactForm submit triggered', [
            'formData' => [
                'name' => $this->name,
                'phone' => $this->phone,
                'formMessage' => $this->formMessage,
            ]
        ]);

        if (!$this->isOpen || $this->state !== 'form') {
            Log::warning('ContactForm submit attempted in invalid state', [
                'isOpen' => $this->isOpen,
                'state' => $this->state
            ]);
            return;
        }

        $validated = $this->validate();

        try {
            Mail::to(config('mail.contact_recipient', 'office@landgrou.com'))
                ->send(new ContactFormSubmitted($validated));

            $this->state = 'success';
            $this->resetForm();
            session()->flash('message', __('messages.form_submitted'));
            $this->dispatch('formSubmitted');
            Log::info('ContactForm submitted successfully', $validated);
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
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
        Log::info('ContactForm try again', ['state' => $this->state]);
    }

    public function continueFromSuccess()
    {
        $this->closeModal();
        Log::info('ContactForm continued from success', ['state' => $this->state]);
    }

    public function goBack()
    {
        $this->closeModal();
        Log::info('ContactForm go back', ['state' => $this->state]);
    }

    private function resetForm()
    {
        $this->reset(['name', 'phone', 'formMessage']);
        $this->resetErrorBag();
        Log::info('ContactForm form reset');
    }

    public function render()
    {
        return view('livewire.components.contact-form');
    }
}
