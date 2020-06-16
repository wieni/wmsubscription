<?php

namespace Drupal\wmsubscription;

interface SubscriptionEventDispatcherInterface
{
    /**
     * This method is called when a subscriber is removed from the tool.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     */
    public function onUnsubscribe(ListInterface $list, PayloadInterface $payload): void;

    /**
     * This method is called when a subscriber is updated in the tool.
     *
     * @param ListInterface $list
     * @param PayloadInterface $payload
     */
    public function onSubscriberUpdate(ListInterface $list, PayloadInterface $payload): void;
}
