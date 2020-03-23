<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Config\BootstrapConfigStorageFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

class WmsubscriptionServiceProvider implements ServiceModifierInterface
{
    public function alter(ContainerBuilder $container)
    {
        $config = BootstrapConfigStorageFactory::get()->read('wmsubscription.settings');

        if (!empty($config['manager']) && $container->hasDefinition($config['manager'])) {
            $container->setDefinition('wmsubscription.manager', $container->getDefinition($config['manager']));
        }
    }
}
