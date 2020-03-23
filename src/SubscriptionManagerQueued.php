<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\wmsubscription\Exception\AlreadySubscribedException;
use Drupal\wmsubscription\Plugin\QueueWorker\SubscriptionQueue;
use Drupal\wmsubscription\QueueDatabase\UniqueSubscriptionQueue;
use InvalidArgumentException;
use RuntimeException;

class SubscriptionManagerQueued extends SubscriptionManager
{
    /** @var UniqueSubscriptionQueue */
    protected $queue;
    /** @var SubscriptionManager */
    protected $manager;

    public function __construct(
        ConfigFactoryInterface $configFactory,
        SubscriptionToolManager $toolManager,
        QueueFactory $queueFactory
    ) {
        parent::__construct($configFactory, $toolManager);
        $this->queue = $queueFactory->get(SubscriptionQueue::ID);

        if (!$this->queue instanceof UniqueSubscriptionQueue) {
            throw new InvalidArgumentException('The subscription queue is not configured to be unique. Please add the following to settings.php: "$settings[\'queue_service_wmsubscription_subscriptions\'] = \'wmsubscription.queue.unique_subscription\';"');
        }
    }

    public function addSubscriber(ListInterface $list, PayloadInterface $payload, string $operation = self::OPERATION_CREATE_OR_UPDATE): void
    {
        if ($operation === self::OPERATION_CREATE && $this->isSubscribed($list, $payload)) {
            throw new AlreadySubscribedException;
        }

        $status = $this->queue->createItem([$list, $payload, $operation]);

        if ($status === false) {
            throw new RuntimeException('wmsubscription: Error while adding subscription to queue.');
        }
    }

    public function isSubscribed(ListInterface $list, PayloadInterface $payload): bool
    {
        return $this->queue->hasItem([$list, $payload])
            || parent::isSubscribed($list, $payload);
    }
}
