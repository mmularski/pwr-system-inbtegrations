<?php
namespace Newsletter\Saver\Model;
use Newsletter\Saver\Api\NewsletterInterface;

class Newsletter implements NewsletterInterface
{

    /**
     * Returns greeting message to client
     *
     * @api
     * @param string $email Email address
     * @return string Greeting message in newsletter
     */
    public function save($email)
    {
        return "Helo " . $email;
    }
}