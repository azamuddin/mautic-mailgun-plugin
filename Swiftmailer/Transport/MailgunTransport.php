<?php

/*
 * @copyright   2019 Arrowfunxtion. All rights reserved
 * @author      Muhammad Azamuddin<mas.azamuddin@gmail.com>
 *
 * @link        http://arrowfunxtion.com/international
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\AFMailgunBundle\Swiftmailer\Transport;

use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\EmailBundle\Model\TransportCallback;
use Mautic\EmailBundle\Swiftmailer\Transport\CallbackTransportInterface;
use Mautic\LeadBundle\Entity\DoNotContact;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MailgunTransport.
 */
class MailgunTransport extends \Swift_SmtpTransport implements CallbackTransportInterface
{
    /**
     * @var bool
     */
    private $sandboxMode;

    /**
     * @var string
     */
    private $sandboxMail;

    /**
     * @var TransportCallback
     */
    private $transportCallback;

    /**
     * {@inheritdoc}
     */
    public function __construct(TransportCallback $transportCallback, $host = 'smtp.mailgun.org',  $username = null, $password = null, $sandboxMode = false, $sandboxMail = '', $port = 587)
    {
        parent::__construct($host, $port, 'tls');
        $this->setAuthMode('login');
        $this->setUsername($username);
        $this->setPassword($password);

        $this->setSandboxMode($sandboxMode);
        $this->setSandboxMail($sandboxMail);

        $this->transportCallback = $transportCallback;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param null                $failedRecipients
     *
     * @return int|void
     *
     * @throws \Exception
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        // add leadIdHash to track this email
        if (isset($message->leadIdHash)) {
            // contact leadidHeash and email to be sure not applying email stat to bcc
            $message->getHeaders()->removeAll('X-Mailgun-Variables');
            $message->getHeaders()->addTextHeader('X-Mailgun-Variables', '{"CUSTOMID":"' . $message->leadIdHash . '-' . key($message->getTo()) . '"}');
        }

        if ($this->isSandboxMode()) {
            $message->setSubject(key($message->getTo()) . ' - ' . $message->getSubject());
            $message->setTo($this->getSandboxMail());
        }

        parent::send($message, $failedRecipients);
    }

    /**
     * Returns a "transport" string to match the URL path /mailer/{transport}/callback.
     *
     * @return mixed
     */
    public function getCallbackPath()
    {
        return 'mailgun';
    }

    /**
     * Handle response.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function processCallbackRequest(Request $request)
    {
        $postData = json_decode($request->getContent(), true);

        if (is_array($postData) && isset($postData['event-data'])) {
            // Mailgun API callback
            $events = [
                $postData['event-data'],
            ];
        } else {
            // response must be an array
            return null;
        }

        foreach ($events as $event) {
            if (!in_array($event['event'], ['bounce', 'rejected', 'complained', 'unsubscribed', 'permanent_fail', 'failed'])) {
                continue;
            }
            $reason = $event['event'];
            if ($event['event'] === 'bounce' || $event['event'] === 'rejected' || $event['event'] === 'permanent_fail' || $event['event'] === 'failed') {
                if (!empty($event['delivery-status']['message'])) {
                    $reason = $event['delivery-status']['message'];
                }elseif (!empty($event['delivery-status']['description'])) {
                    $reason = $event['delivery-status']['description'];
                }
                $type = DoNotContact::BOUNCED;
            } elseif ($event['event'] === 'complained') {
                if (isset($event['delivery-status']['message'])) {
                    $reason = $event['delivery-status']['message'];
                }
                $type = DoNotContact::UNSUBSCRIBED;
            } elseif ($event['event'] === 'unsubscribed') {
                $reason = 'User unsubscribed';
                $type = DoNotContact::UNSUBSCRIBED;
            } else {
                continue;
            }

            if (isset($event['user-variables']['CUSTOMID'])) {
                $event['CustomID'] = $event['user-variables']['CUSTOMID'];
            }

            if (isset($event['CustomID']) && $event['CustomID'] !== '' && strpos($event['CustomID'], '-', 0) !== false) {
                $fistDashPos = strpos($event['CustomID'], '-', 0);
                $leadIdHash = substr($event['CustomID'], 0, $fistDashPos);
                $leadEmail = substr($event['CustomID'], $fistDashPos + 1, strlen($event['CustomID']));
                if ($event['recipient'] == $leadEmail) {
                    $this->transportCallback->addFailureByHashId($leadIdHash, $reason, $type);
                }
            } else {
                $this->transportCallback->addFailureByAddress($event['recipient'], $reason, $type);
            }
        }
    }

    /**
     * @return bool
     */
    private function isSandboxMode()
    {
        return $this->sandboxMode;
    }

    /**
     * @param bool $sandboxMode
     */
    private function setSandboxMode($sandboxMode)
    {
        $this->sandboxMode = $sandboxMode;
    }

    /**
     * @return string
     */
    private function getSandboxMail()
    {
        return $this->sandboxMail;
    }

    /**
     * @param string $sandboxMail
     */
    private function setSandboxMail($sandboxMail)
    {
        $this->sandboxMail = $sandboxMail;
    }
}
