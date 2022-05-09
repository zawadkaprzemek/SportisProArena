<?php

namespace App\Service;

use Swift_Message;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var MailerInterface
     */
    private $mailerGmail;

    public function __construct(ParameterBagInterface $parameterBag,MailerInterface $mailerGmail)
    {
        $this->mailerGmail = $mailerGmail;  
        $this->parameterBag = $parameterBag;
    }

    private function getParameter($name)
    {
        return $this->parameterBag->get($name);
    }

    public function sendMailGmail(string $recipent,string $subject,string $body,array $attachments=[])
    {
        $email = (new Email())
            ->from($this->getParameter('mailer_send_from'))
            ->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->html($body)
            ->addTo($recipent)
            ->addBcc('zawadkaprzemek@gmail.com')
        ;
        $email->getHeaders()->addTextHeader('X-Transport', 'main');
        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $this->mailerGmail->send($email);
        try {
            $this->mailerGmail->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            dd($e);
        }

    }

}