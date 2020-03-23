<?php

namespace Drupal\wmsubscription;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\wmsubscription\Exception\AlreadySubscribedException;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /** @var ConfigFactoryInterface */
    protected $configFactory;
    /** @var SubscriptionToolManager */
    protected $toolManager;

    public function __construct(
        ConfigFactoryInterface $configFactory,
        SubscriptionToolManager $toolManager
    ) {
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

    public function isSubscribed(ListInterface $list, PayloadInterface $payload): bool
    {
        return $this->getSubscriptionTool()->isSubscribed($list, $payload);
    }

    public function isUpdatable(ListInterface $list, PayloadInterface $payload): bool
    {
        return $this->getSubscriptionTool()->isUpdatable($list, $payload);
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
