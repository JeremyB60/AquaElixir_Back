<?php


namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailConfirmationException extends HttpException
{
    private $recipientEmail;

    public function __construct($recipientEmail,
     $message = 'Failed to send email confirmation',
      $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->recipientEmail = $recipientEmail;
    }

    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }
}

