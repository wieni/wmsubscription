<?php

namespace Drupal\wmsubscription\Exception;

class EmailRejectedException extends SubscriptionException
{
    public const MESSAGE_KEY = 'email_rejected';
}
