<?php

namespace Drupal\wmsubscription\Event;

use Drupal\wmsubscription\ListInterface;
use Drupal\wmsubscription\PayloadInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class SubscriberEventBase extends Event
{
    /** @var ListInterface */
    protected $list;
    /** @var PayloadInterface */
    protected $subscriber;

    public function __construct(
        ListInterface $list,
        PayloadInterface $subscriber
    ) {
        $this->list = $list;
        $this->subscriber = $subscriber;
    }

    public function getList(): ListInterface
    {
        return $this->list;
    }

    public function getSubscriber(): PayloadInterface
    {
        return $this->subscriber;
    }
}
