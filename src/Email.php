<?php

namespace EasyMVC\Email;

use Latte\Engine;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;

/**
 * Class Emvc_Email (PHP version 7.1)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2017-2018, rmsoft.be. (http://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     2.0.0
 * @package     EasyMVC\Email
 */
class Email
{
    private $email;
    private $from;

    private $use_smtp;
    private $email_host;
    private $email_username;
    private $email_password;
    private $email_security;

    /**
     * Email constructor.
     * @param $email_from
     */
    public function __construct($email_from = EMAIL_FROM)
    {
        $this->email = new Message();
        $this->from = $email_from;
    }

    /**
     * Use this function when you use it in your own project
     *
     * @param string $use_smtp
     * @param string $email_host
     * @param string $email_username
     * @param string $email_password
     * @param string $email_security
     */
    public function setup(string $use_smtp,
                          string $email_host,
                          string $email_username,
                          string $email_password,
                          string $email_security): void
    {
        $this->use_smtp = $use_smtp;
        $this->email_host = $email_host;
        $this->email_username = $email_username;
        $this->email_password = $email_password;
        $this->email_security = $email_security;
    }

    /**
     * Use this function when you use it in the EasyMVC framework
     */
    public function emvc_config(): void
    {
        $this->use_smtp = USE_SMTP;
        $this->email_host = EMAIL_HOST;
        $this->email_username = EMAIL_USERNAME;
        $this->email_password = EMAIL_password;
        $this->email_security = EMAIL_SECURITY;
    }

    /**
     * For setting the sender of the E-mail
     *
     * @param string $email_from
     */
    public function setFrom(string $email_from): void
    {
        $this->from = $email_from;
    }

    /**
     * Prepare a plain text E-mail
     *
     * @param array $email_to
     * @param array|null $email_cc
     * @param array|null $email_bcc
     * @param string $subject
     * @param string $body
     */
    public function setTextMessage(array $email_to,
                                   array $email_cc = null,
                                   array $email_bcc = EMAIL_BCC,
                                   string $subject,
                                   string $body): void
    {
        $this->email->setFrom($this->from);
        foreach ($email_to as $value) {
            $this->email->addTo($value);
        }
        if ($email_cc != null) {
            foreach ($email_cc as $value) {
                $this->email->addCc($value);
            }
        }
        if ($email_bcc != null) {
            foreach ($email_bcc as $value) {
                $this->email->addBcc($value);
            }
        }
        $this->email->setSubject($subject);
        $this->email->setBody($body);
    }

    /**
     * Prepare a HTML E-mail
     *
     * @param array $email_to
     * @param array|null $email_cc
     * @param array|null $email_bcc
     * @param string $subject
     * @param string $body
     */
    public function setHtmlMessage(array $email_to,
                                   array $email_cc = null,
                                   array $email_bcc = EMAIL_BCC,
                                   string $subject,
                                   string $body): void
    {
        $this->email->setFrom($this->from);
        foreach ($email_to as $value) {
            $this->email->addTo($value);
        }
        if ($email_cc != null) {
            foreach ($email_cc as $value) {
                $this->email->addCc($value);
            }
        }
        if ($email_bcc != null) {
            foreach ($email_bcc as $value) {
                $this->email->addBcc($value);
            }
        }
        $this->email->setSubject($subject);
        $this->email->setHtmlBody($body);
    }

    /**
     * Use this after your E-mail has been prepared
     */
    public function sendMail(): void
    {
        if ($this->use_smtp) {
            $mailer = new SmtpMailer([
                'host' => $this->email_host,
                'username' => $this->email_username,
                'password' => $this->email_password,
                'secure' => $this->email_security
            ]);
        } else {
            $mailer = new SendmailMailer();
        }
        $mailer->send($this->email);
    }

    /**
     * Use Latte for rendering HTML E-mails in the EasyMVC framework
     *
     * @param string $latteFile
     * @param array $data
     * @return string
     */
    public function emvc_renderHtml(string $latteFile, array $data): string
    {
        $latte = new Engine();
        $latte->setTempDirectory($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/tmp/latte');
        return $latte->renderToString($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/public/latte/' . $latteFile, $data);
    }

    /**
     * Use Latte for rendering HTML E-mails in your own project
     *
     * @param string $latteFile
     * @param array $data
     * @param string $tempFolderLatte
     * @return string
     */
    public function renderHtml(string $latteFile, array $data, string $tempFolderLatte = '/tmp/latte'): string
    {
        $latte = new Engine();
        $latte->setTempDirectory($tempFolderLatte);
        return $latte->renderToString($latteFile, $data);
    }
}

/** End of File: Email.php **/