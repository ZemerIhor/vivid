<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $subject;
    public $formMessage;
    public $rating;

    /**
     * Конструктор принимает данные формы и устанавливает значения с проверкой на null.
     *
     * @param array $data Данные из формы
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? 'N/A';
        $this->email = $data['email'] ?? 'N/A';
        $this->phone = $data['phone'] ?? 'N/A';
        $this->subject = $data['subject'] ?? 'N/A';
        $this->formMessage = $data['formMessage'] ?? 'N/A';
        $this->rating = $data['rating'] ?? 0;
    }

    /**
     * Строит письмо с указанием шаблона и темы.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-form')
            ->subject('New Contact Form Submission');
    }
}
