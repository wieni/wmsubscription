<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueInterface;
use Drupal\wmsubscription\Exception\AlreadyQueuedException;
use Drupal\wmsubscription\Exception\AlreadySubscribedException;
use Drupal\wmsubscription\Plugin\QueueWorker\SubscriptionQueue;
use Drupal\wmsubscription\Plugin\QueueWorker\SubscriptionUpdateQueue;
use Drupal\wmsubscription\Plugin\QueueWorker\UnsubscriptionQueue;
use Drupal\wmsubscription\QueueDatabase\UniqueSubscriptionQueue;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubscriptionManagerQueued extends SubscriptionManager
{
    /** @var QueueInterface */
    protected $subscriptionQueue;
    /** @var QueueInterface */
    protected $subscriptionUpdateQueue;
    /** @var QueueInterface */
    protected $unsubscriptionQueue;
    /** @var SubscriptionManager */
    protected $manager;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ConfigFactoryInterface $configFactory,
        SubscriptionToolManager $toolManager,
        QueueFactory $queueFactory
    ) {
        parent::__construct($eventDispatcher, $configFactory, $toolManager);
        $this->subscriptionQueue = $queueFactory->get(SubscriptionQueue::ID);
        $this->subscriptionUpdateQueue = $queueFactory->get(SubscriptionUpdateQueue::ID);
        $this->unsubscriptionQueue = $queueFactory->get(UnsubscriptionQueue::ID);
    }

    public function addSubscriber(ListInterface $list, PayloadInterface $payload, string $operation = self::OPERATION_CREATE_OR_UPDATE): void
    {
        if ($this->subscriptionQueue instanceof UniqueSubscriptionQueue && $this->subscriptionQueue->hasItem([$list, $payload, $operation])) {
            throw new AlreadyQueuedException;
        }

        if ($operation === self::OPERATION_CREATE && parent::isSubscribed($list, $payload)) {
            throw new AlreadySubscribedException;
        }

        $status = $this->subscriptionQueue->createItem([$list, $payload, $operation]);

        if ($status === false) {
            throw new RuntimeException('wmsubscription: Error while adding subscription to queue.');
        }
    }

    public function isSubscribed(ListInterface $list, PayloadInterface $payload): bool
    {
        if ($this->subscriptionQueue instanceof UniqueSubscriptionQueue) {
            return $this->subscriptionQueue->hasItem([$list, $payload])
                || parent::isSubscribed($list, $payload);
        }

        return parent::isSubscribed($list, $payload);
    }

    public function onUnsubscribe(ListInterface $list, PayloadInterface $payload): void
    {
        $status = $this->unsubscriptionQueue->createItem([$list, $payload]);

        if ($status === false) {
            throw new RuntimeException('wmsubscription: Error while adding unsubscription to queue.');
        }
    }

    public function onSubscriberUpdate(ListInterface $list, PayloadInterface $payload): void
    {
        $status = $this->subscriptionUpdateQueue->createItem([$list, $payload]);

        if ($status === false) {
            throw new RuntimeException('wmsubscription: Error while adding subscription update to queue.');
        }
    }
}
