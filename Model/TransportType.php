<?php

/*
 * @copyright   2019 Arrowfunxtion. All rights reserved
 * @author      Muhammad Azamuddin<mas.azamuddin@gmail.com>
 *
 * @link        http://arrowfunxtion.com/international
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\AFMailgunBundle\Model;

use Mautic\EmailBundle\Model\TransportType as TransportTypeBase;

class TransportType extends TransportTypeBase
{

    /**
     * @var array
     */
    private $transportTypes = [
        'mautic.transport.amazon' => 'mautic.email.config.mailer_transport.amazon',
        'mautic.transport.mailgun' => 'mauticplugin.af.mailgun.config.mailer_transport.mailgun',
        'mautic.transport.elasticemail' => 'mautic.email.config.mailer_transport.elasticemail',
        'gmail' => 'mautic.email.config.mailer_transport.gmail',
        'mautic.transport.mandrill' => 'mautic.email.config.mailer_transport.mandrill',
        'mautic.transport.mailjet' => 'mautic.email.config.mailer_transport.mailjet',
        'smtp' => 'mautic.email.config.mailer_transport.smtp',
        'mail' => 'mautic.email.config.mailer_transport.mail',
        'mautic.transport.postmark' => 'mautic.email.config.mailer_transport.postmark',
        'mautic.transport.sendgrid' => 'mautic.email.config.mailer_transport.sendgrid',
        'mautic.transport.sendgrid_api' => 'mautic.email.config.mailer_transport.sendgrid_api',
        'sendmail' => 'mautic.email.config.mailer_transport.sendmail',
        'mautic.transport.sparkpost' => 'mautic.email.config.mailer_transport.sparkpost',
    ];

    /**
     * @var array
     */
    private $showHost = [
        'smtp',
    ];

    /**
     * @var array
     */
    private $showPort = [
        'smtp',
    ];

    /**
     * @var array
     */
    private $showUser = [
        'mautic.transport.mailjet',
        'mautic.transport.sendgrid',
        'mautic.transport.elasticemail',
        'mautic.transport.amazon',
        'mautic.transport.postmark',
        'gmail',
        'mautic.transport.mailgun',
        // smtp is left out on purpose as the auth_mode will manage displaying this field
    ];

    /**
     * @var array
     */
    private $showPassword = [
        'mautic.transport.mailjet',
        'mautic.transport.sendgrid',
        'mautic.transport.elasticemail',
        'mautic.transport.amazon',
        'mautic.transport.postmark',
        'gmail',
        'mautic.transport.mailgun',
        // smtp is left out on purpose as the auth_mode will manage displaying this field
    ];

    /**
     * @var array
     */
    private $showApiKey = [
        'mautic.transport.sparkpost',
        'mautic.transport.mandrill',
        'mautic.transport.sendgrid_api',
    ];

    /**
     * @param $serviceId
     * @param $translatableAlias
     * @param $showHost
     * @param $showPort
     * @param $showUser
     * @param $showPassword
     * @param $showApiKey
     */
    public function addTransport($serviceId, $translatableAlias, $showHost, $showPort, $showUser, $showPassword, $showApiKey)
    {

        $this->transportTypes[$serviceId] = $translatableAlias;

        if ($showHost) {
            $this->showHost[] = $serviceId;
        }

        if ($showPort) {
            $this->showPort[] = $serviceId;
        }

        if ($showUser) {
            $this->showUser[] = $serviceId;
        }

        if ($showPassword) {
            $this->showPassword[] = $serviceId;
        }

        if ($showApiKey) {
            $this->showApiKey[] = $serviceId;
        }
    }

    /**
     * @return array
     */
    public function getTransportTypes()
    {
        return $this->transportTypes;
    }

    /**
     * @return string
     */
    public function getServiceRequiresHost()
    {
        return $this->getString($this->showHost);
    }

    /**
     * @return string
     */
    public function getServiceRequiresPort()
    {
        return $this->getString($this->showPort);
    }

    /**
     * @return string
     */
    public function getServiceRequiresUser()
    {
        return $this->getString($this->showUser);
    }

    /**
     * @return string
     */
    public function getServiceDoNotNeedUser()
    {
        // The auth_mode data-show-on will handle smtp
        $tempTransports = $this->transportTypes;
        unset($tempTransports['smtp']);

        $transports = array_keys($tempTransports);
        $doNotRequireUser = array_diff($transports, $this->showUser);

        return $this->getString($doNotRequireUser);
    }

    public function getServiceDoNotNeedPassword()
    {
        // The auth_mode data-show-on will handle smtp
        $tempTransports = $this->transportTypes;
        unset($tempTransports['smtp']);

        $transports = array_keys($tempTransports);
        $doNotRequireUser = array_diff($transports, $this->showPassword);

        return $this->getString($doNotRequireUser);
    }

    /**
     * @return string
     */
    public function getServiceRequiresPassword()
    {
        return $this->getString($this->showPassword);
    }

    /**
     * @return string
     */
    public function getServiceRequiresApiKey()
    {
        return $this->getString($this->showApiKey);
    }

    /**
     * @return string
     */
    public function getSmtpService()
    {
        return '"smtp"';
    }

    /**
     * @return string
     */
    public function getAmazonService()
    {
        return '"mautic.transport.amazon"';
    }

    /**
     * @return string
     */
    public function getMailjetService()
    {
        return '"mautic.transport.mailjet"';
    }

    public function getMailgunService()
    {
        return '"mautic.transport.mailgun"';
    }

    /**
     * @deprecated 2.14.0 to be removed in 3.0
     *
     * @return string
     */
    public function getServiceRequiresLogin()
    {
        return $this->getServiceRequiresUser();
    }

    /**
     * @deprecated 2.14.0 to be removed in 3.0
     *
     * @return string
     */
    public function getServiceDoNotNeedLogin()
    {
        return $this->getServiceDoNotNeedUser();
    }

    /**
     * @param array $services
     *
     * @return string
     */
    private function getString(array $services)
    {
        return '"' . implode('","', $services) . '"';
    }
}
