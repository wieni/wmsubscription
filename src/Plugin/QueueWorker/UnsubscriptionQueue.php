<?php

namespace Drupal\wmsubscription\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\wmsubscription\SubscriptionManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = \Drupal\wmsubscription\Plugin\QueueWorker\UnsubscriptionQueue::ID,
 *   title = @Translation("Unsubscription events"),
 *   cron = {"time" = 30}
 * )
 */
class UnsubscriptionQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface
{
    public const ID = 'wmsubscription_unsubscription';

    /** @var SubscriptionManagerInterface */
    protected $manager;

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $pluginId,
        $pluginDefinition
    ) {
        $instance = new static($configuration, $pluginId, $pluginDefinition);
        $instance->manager = $container->get('wmsubscription.manager.direct');

        return $instance;
    }

    public function processItem($item)
    {
        $this->manager->onUnsubscribe(...$item);
    }
}
