<?php

namespace App\Manager;

use Symfony\Component\Mailer\MailerInterface;

class MailerManager
{

    public function __construct(
        private MailerInterface $Mailer
    ) {
    }

    public function sendSystemEmail(string $to, string $subject, string $text = '', string $html = ''): bool
    {
        if(!$text && !$html)
        {
            throw new Exception('No empty email can be sent by system.');
        }
        $email = (new Email())
            ->from('system@example.com')
            ->to($to)
            ->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($text)
            ->html($html);

        return $this->mailer->send($email);
    }
}
