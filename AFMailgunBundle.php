<?php

/*
 * @copyright   2019 Arrowfunxtion. All rights reserved
 * @author      Muhammad Azamuddin<mas.azamuddin@gmail.com>
 *
 * @link        http://arrowfunxtion.com/international
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\AFMailgunBundle;

use MauticPlugin\AFMailgunBundle\DependencyInjection\Compiler\EmailTransportPass;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AFMailgunBundle.
 */
class AFMailgunBundle extends PluginBundleBase
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EmailTransportPass());
    }
}
