<?php

namespace App;

use App\Config;
use Mailgun\Mailgun;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        /*
        $mg = new Mailgun(Config::MAILGUN_API_KEY);
        $domain = Config::MAILGUN_DOMAIN;

        $mg->sendMessage($domain, ['from'    => 'willem.leonardo@gmail.com',
                                   'to'      => $to,
                                   'subject' => $subject,
                                   'text'    => $text,
                                   'html'    => $html]);
        */
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 1;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = Config::MAILER_USERNAME;                // SMTP username
        $mail->Password   = Config::MAILER_PASSWORD;                // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        $mail->setFrom(Config::MAILER_USERNAME);
        $mail->addAddress($to);                                     // Name is optional

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $text;
        $mail->AltBody = $html;

        $mail->send();
        echo 'Message has been sent';
    }
}
