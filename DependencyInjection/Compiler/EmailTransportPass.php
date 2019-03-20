<?php
/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\AFMailgunBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EmailTransportPass.
 */
class EmailTransportPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {

        // override the mautic transport type with
        // our custom transport type class definition
        // which includes mailgun definition
        $container
            ->register(
                'mautic.email.transport_type',
                'MauticPlugin\AFMailgunBundle\Model\TransportType'
            );
    }
}
