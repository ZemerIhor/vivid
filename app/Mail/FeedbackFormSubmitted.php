<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $comment;

    /**
     * Конструктор принимает данные формы и устанавливает значения с проверкой на null.
     *
     * @param array $data Данные из формы
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? 'N/A';
        $this->phone = $data['phone'] ?? 'N/A';
        $this->comment = $data['comment'] ?? 'N/A';
    }

    /**
     * Строит письмо с указанием шаблона и темы.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.feedback-form')
            ->subject(__('messages.feedback_form.email_subject'));
    }
}
