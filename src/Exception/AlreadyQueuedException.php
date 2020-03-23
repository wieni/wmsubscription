<?php

namespace Drupal\wmsubscription\Exception;

class AlreadyQueuedException extends SubscriptionException
{
    public const MESSAGE_KEY = 'already_subscribed';
}
