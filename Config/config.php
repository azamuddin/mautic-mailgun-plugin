<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'services' => [
        'other' => [
            'mautic.transport.mailgun' => [
                'class' => 'MauticPlugin\AFMailgunBundle\Swiftmailer\Transport\MailgunTransport',
                'serviceAlias' => 'swiftmailer.mailer.transport.%s',
                'arguments' => [
                    'mautic.email.model.transport_callback',
                    '%mautic.mailer_mailgun_sandbox%',
                    '%mautic.mailer_mailgun_sandbox_default_mail%',
                ],
                'methodCalls' => [
                    'setUsername' => ['%mautic.mailer_user%'],
                    'setPassword' => ['%mautic.mailer_password%'],
                ],
            ],
        ],
    ],
    'parameters' => [
        'mailer_mailgun_sandbox' => false,
        'mailer_mailgun_sandbox_default_mail' => null,
    ],
];
