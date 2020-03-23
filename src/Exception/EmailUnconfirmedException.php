<?php

namespace Drupal\wmsubscription\Exception;

class EmailUnconfirmedException extends SubscriptionException
{
    public const MESSAGE_KEY = 'email_unconfirmed';
}
