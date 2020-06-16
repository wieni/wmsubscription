<?php

namespace Drupal\wmsubscription\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\wmsubscription\Exception\AlreadySubscribedException;
use Drupal\wmsubscription\Exception\ValidationFailedException;
use Drupal\wmsubscription\SubscriptionManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = \Drupal\wmsubscription\Plugin\QueueWorker\UnsubscriptionQueue::ID,
 *   title = @Translation("Subscription updates"),
 *   cron = {"time" = 30}
 * )
 */
class SubscriptionUpdateQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface
{
    public const ID = 'wmsubscription_subscription_update';

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
        $this->manager->onSubscriberUpdate(...$item);
    }
}
