<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizationInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $organizationName,
        private readonly string $url,
    ) {
        $this->onQueue('erp-delivery');
    }

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Convite para o OpenFinance Platform')
            ->greeting('Você recebeu um convite')
            ->line("Você foi convidado para acessar {$this->organizationName}.")
            ->action('Aceitar convite', $this->url)
            ->line('O convite expira em 72 horas.');
    }
}
