<?php

namespace Drupal\wmsubscription_dead_letter_queue\QueueDatabase;

use Drupal\dead_letter_queue\Queue\DeadLetterQueueDatabaseFactory;

class UniqueDeadLetterDatabaseQueueFactory extends DeadLetterQueueDatabaseFactory
{
    public function get($name)
    {
        return new UniqueDeadLetterDatabaseQueue(
            $name,
            $this->connection,
            $this->queueManager,
            $this->configFactory,
            $this->logger
        );
    }
}
