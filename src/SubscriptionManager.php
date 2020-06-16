<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\wmsubscription\Event\SubscriberUpdateEvent;
use Drupal\wmsubscription\Event\UnsubscribeEvent;
use Drupal\wmsubscription\Exception\AlreadySubscribedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubscriptionManager implements SubscriptionManagerInterface, SubscriptionEventDispatcherInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var ConfigFactoryInterface */
    protected $configFactory;
    /** @var SubscriptionToolManager */
    protected $toolManager;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ConfigFactoryInterface $configFactory,
        SubscriptionToolManager $toolManager
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->configFactory = $configFactory;
        $this->toolManager = $toolManager;
    }

    public function addSubscriber(ListInterface $list, PayloadInterface $payload, string $operation = self::OPERATION_CREATE_OR_UPDATE): void
    {
        if ($operation === self::OPERATION_CREATE && $this->isSubscribed($list, $payload)) {
            throw new AlreadySubscribedException;
        }

        $this->getSubscriptionTool()->addSubscriber($list, $payload);
    }

    public function getSubscriber(ListInterface $list, PayloadInterface $payload): ?PayloadInterface
    {
        return $this->getSubscriptionTool()->getSubscriber($list, $payload);
    }

    public function isSubscribed(ListInterface $list, PayloadInterface $payload): bool
    {
        return $this->getSubscriptionTool()->isSubscribed($list, $payload);
    }

    public function isUpdatable(ListInterface $list, PayloadInterface $payload): bool
    {
        return $this->getSubscriptionTool()->isUpdatable($list, $payload);
    }

    public function onUnsubscribe(ListInterface $list, PayloadInterface $payload): void
    {
        $this->eventDispatcher->dispatch(
            WmsubscriptionEvents::UNSUBSCRIBE,
            new UnsubscribeEvent($list, $payload)
        );
    }

    public function onSubscriberUpdate(ListInterface $list, PayloadInterface $payload): void
    {
        $this->eventDispatcher->dispatch(
            WmsubscriptionEvents::SUBSCRIBER_UPDATE,
            new SubscriberUpdateEvent($list, $payload)
        );
    }

    protected function getSubscriptionTool(): SubscriptionToolInterface
    {
        $config = $this->configFactory->get('wmsubscription.settings');

        if (
            ($id = $config->get('tool'))
            && $this->toolManager->hasDefinition($id)
        ) {
            return $this->toolManager->createInstance($id);
        }

        throw new \RuntimeException('wmsubscription: No subscription tool configured.');
    }
}
