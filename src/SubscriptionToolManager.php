<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\wmsubscription\Annotation\SubscriptionTool;

class SubscriptionToolManager extends DefaultPluginManager
{
    public function __construct(
        \Traversable $namespaces,
        CacheBackendInterface $cacheBackend,
        ModuleHandlerInterface $moduleHandler
    ) {
        parent::__construct(
            'Plugin/SubscriptionTool',
            $namespaces,
            $moduleHandler,
            SubscriptionToolInterface::class,
            SubscriptionTool::class
        );
        $this->alterInfo('wmsubscription_info');
        $this->setCacheBackend($cacheBackend, 'wmsubscription_info_plugins');
    }
}
