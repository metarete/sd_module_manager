<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerGeneratorTest
{
    private $mailer;
    private $params;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params= $params;
    }

    public function EmailTest($emailTest)
    {
        $sender = $this->params->get('app.mailer_notification_sender');

        $email = (new Email())
        ->from($sender)
        ->to($emailTest)
        ->subject('Email di test')
        ->text('Email di prova');
        



    $this->mailer->send($email);
    }
}