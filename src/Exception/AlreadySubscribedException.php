<?php

namespace Drupal\wmsubscription\Exception;

class AlreadySubscribedException extends SubscriptionException
{
    public const MESSAGE_KEY = 'already_subscribed';
}
