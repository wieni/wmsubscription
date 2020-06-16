<?php

namespace Drupal\wmsubscription;

use Drupal\wmsubscription\Exception\AlreadySubscribedException;

interface SubscriptionToolInterface
{
    public const OPERATION_CREATE = 'create';
    public const OPERATION_UPDATE = 'update';
    public const OPERATION_CREATE_OR_UPDATE = 'create_update';

    /**
     * Add or update a new list subscriber.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     * @param string $operation
     *
     * @throws AlreadySubscribedException
     */
    public function addSubscriber(ListInterface $list, PayloadInterface $payload, string $operation = self::OPERATION_CREATE_OR_UPDATE): void;

    /**
     * Get all information about a list subscriber.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     */
    public function getSubscriber(ListInterface $list, PayloadInterface $payload): ?PayloadInterface;

    /**
     * Check if someone is already subscribed to a list.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     *
     * @return bool
     */
    public function isSubscribed(ListInterface $list, PayloadInterface $payload): bool;

    /**
     * Check if an existing subscriber already exists.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     *
     * @return bool
     */
    public function isUpdatable(ListInterface $list, PayloadInterface $payload): bool;
}
