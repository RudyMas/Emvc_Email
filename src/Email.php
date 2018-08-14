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
 * @version     2.1.0.27
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
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $security
     * @param bool $use_smtp
     */
    public function setup(string $host, string $username, string $password, string $security, bool $use_smtp = true): void
    {
        $this->use_smtp = $use_smtp;
        $this->email_host = $host;
        $this->email_username = $username;
        $this->email_password = $password;
        $this->email_security = $security;
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
     * @param string $from
     */
    public function setFrom(string $from = EMAIL_FROM): void
    {
        $this->from = $from;
    }

    /**
     * Prepare a plain text E-mail
     *
     * @param array $to
     * @param string $subject
     * @param string $body
     * @param array|null $attachment
     * @param array|null $cc
     * @param array $bcc
     */
    public function setTextMessage(array $to,
                                   string $subject,
                                   string $body,
                                   array $attachment = null,
                                   array $cc = null,
                                   array $bcc = EMAIL_BCC): void
    {
        $this->email->setFrom($this->from);
        foreach ($to as $value) {
            $this->email->addTo($value);
        }
        if ($cc != null) {
            foreach ($cc as $value) {
                $this->email->addCc($value);
            }
        }
        if ($bcc != null) {
            foreach ($bcc as $value) {
                $this->email->addBcc($value);
            }
        }
        if ($attachment != null) {
            foreach ($attachment as $file) {
                $this->email->addAttachment($file);
            }
        }
        $this->email->setSubject($subject);
        $this->email->setBody($body);
    }

    /**
     * Prepare a HTML E-mail
     *
     * @param array $to
     * @param string $subject
     * @param string $body
     * @param array|null $attachment
     * @param array|null $cc
     * @param array|null $bcc
     */
    public function setHtmlMessage(array $to,
                                   string $subject,
                                   string $body,
                                   array $attachment = null,
                                   array $cc = null,
                                   array $bcc = EMAIL_BCC): void
    {
        $this->email->setFrom($this->from);
        foreach ($to as $value) {
            $this->email->addTo($value);
        }
        if ($cc != null) {
            foreach ($cc as $value) {
                $this->email->addCc($value);
            }
        }
        if ($bcc != null) {
            foreach ($bcc as $value) {
                $this->email->addBcc($value);
            }
        }
        if ($attachment != null) {
            foreach ($attachment as $file) {
                $this->email->addAttachment($file);
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
    public function emvcRenderHtml(string $latteFile, array $data): string
    {
        $latte = new Engine();
        $latte->setTempDirectory($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/tmp/latte');
        return $latte->renderToString($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/private/latte/' . $latteFile, $data);
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