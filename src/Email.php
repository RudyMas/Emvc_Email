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
 * @version     1.4.0
 * @package     EasyMVC\Email
 */
class Email
{
    private $email;
    private $from;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->email = new Message();
        $this->from = EMAIL_FROM;
    }

    /**
     * For changing the sender of the E-mail
     *
     * @param string $email
     */
    public function setFrom(string $email): void
    {
        $this->from = $email;
    }

    /**
     * Prepare a plain text E-mail
     *
     * @param array $to
     * @param array|null $cc
     * @param array|null $bcc
     * @param string $subject
     * @param string $body
     */
    public function setTextMessage(array $to, array $cc = null, array $bcc = null, string $subject, string $body): void
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
        } elseif (EMAIL_BCC != null) {
            $this->email->addBcc(EMAIL_BCC);
        }
        $this->email->setSubject($subject);
        $this->email->setBody($body);
    }

    /**
     * Prepare a HTML E-mail
     *
     * @param array $to
     * @param array|null $cc
     * @param array|null $bcc
     * @param string $subject
     * @param string $body
     */
    public function setHtmlMessage(array $to, array $cc = null, array $bcc = null, string $subject, string $body): void
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
        } elseif (EMAIL_BCC != null) {
            $this->email->addBcc(EMAIL_BCC);
        }
        $this->email->setSubject($subject);
        $this->email->setHtmlBody($body);
    }

    /**
     * Use this after your E-mail has been prepared
     */
    public function sendMail(): void
    {
        if (USE_SMTP) {
            $mailer = new SmtpMailer([
                'host' => EMAIL_HOST,
                'username' => EMAIL_USERNAME,
                'password' => EMAIL_PASSWORD,
                'secure' => EMAIL_SECURITY
            ]);
        } else {
            $mailer = new SendmailMailer();
        }
        $mailer->send($this->email);
    }

    /**
     * Use Latte for rendering HTML E-mails
     *
     * @param string $latteFile
     * @param array $data
     * @return string
     */
    public function renderHtml(string $latteFile, array $data): string
    {
        $latte = new Engine();
        $latte->setTempDirectory($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/tmp/latte');
        return $latte->renderToString($_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/public/latte/' . $latteFile, $data);
    }
}

/** End of File: Email.php **/