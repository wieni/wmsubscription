<?php

namespace Drupal\wmsubscription;

final class WmsubscriptionEvents
{
    /**
     * This function is called if a subscriber is removed from the tool.
     *
     * The event object is an instance of
     * @uses \Drupal\wmsubscription\Event\UnsubscribeEvent
     */
    public const UNSUBSCRIBE = 'wmsubscription.unsubscribe';

    /**
     * This function is called when a subscriber is updated in the tool.
     *
     * The event object is an instance of
     * @uses \Drupal\wmsubscription\Event\SubscriberUpdateEvent
     */
    public const SUBSCRIBER_UPDATE = 'wmsubscription.subscriber_update';
}
