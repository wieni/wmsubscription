<?php

namespace Drupal\wmsubscription\QueueDatabase;

use Drupal\Core\Queue\QueueDatabaseFactory;

class UniqueSubscriptionQueueFactory extends QueueDatabaseFactory
{
    public function get($name)
    {
        return new UniqueSubscriptionQueue($name, $this->connection);
    }
}
