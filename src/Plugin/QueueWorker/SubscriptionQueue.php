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
 *   id = \Drupal\wmsubscription\Plugin\QueueWorker\SubscriptionQueue::ID,
 *   title = @Translation("Subscriptions"),
 *   cron = {"time" = 30}
 * )
 */
class SubscriptionQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface
{
    public const ID = 'wmsubscription_subscriptions';

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
        if (!isset($item[2])) {
            $item[2] = SubscriptionManagerInterface::OPERATION_CREATE_OR_UPDATE;
        }

        try {
            $this->manager->addSubscriber(...$item);
        } catch (AlreadySubscribedException $e) {
            // Just ignore, no action needed
        } catch (ValidationFailedException $e) {
            // Just ignore, at this point it's too late to fix this
        }
    }
}
