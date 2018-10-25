<?php
namespace Newsletter\Saver\Api;

interface NewsletterInterface
{
    /**
     * Returns greeting message to client
     *
     * @api
     * @param string $email Email address
     * @return string Greeting message in newsletter
     */
    public function save($email);
}