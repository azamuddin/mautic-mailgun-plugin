<?php

/*
 * @copyright   2019 Arrowfunxtion. All rights reserved
 * @author      Muhammad Azamuddin<mas.azamuddin@gmail.com>
 *
 * @link        http://arrowfunxtion.com/international
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */


return [
    'services' => [
        'other' => [
            'mautic.transport.mailgun' => [
                'class' => \MauticPlugin\AFMailgunBundle\Swiftmailer\Transport\MailgunTransport::class,
                'arguments' => [
                    'mautic.email.model.transport_callback',
                    '%mautic.mailer_host%',
                    '%mautic.mailer_user%',
                    '%mautic.mailer_port%',
                    '%mautic.mailer_password%',
                    '%mautic.mailer_mailgun_sandbox%',
                    '%mautic.mailer_mailgun_sandbox_default_mail%',
                ],
                'tagArguments' => [
                    \Mautic\EmailBundle\Model\TransportType::TRANSPORT_ALIAS => 'mautic.email.config.mailer_transport.mailgun',
                    \Mautic\EmailBundle\Model\TransportType::FIELD_HOST   => true,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_USER      => true,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_PASSWORD      => true,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_PORT      => true,
                ],
                'tag'          => 'mautic.email_transport',
                'serviceAlias' => 'swiftmailer.mailer.transport.%s',
            ],
        ],
    ],
    'parameters' => [
        'mailer_mailgun_sandbox' => false,
        'mailer_mailgun_sandbox_default_mail' => null,
    ],
];
