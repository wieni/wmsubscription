<?php

namespace Drupal\wmsubscription_dead_letter_queue\QueueDatabase;

use Drupal\Core\Database\StatementInterface;
use Drupal\dead_letter_queue\Queue\DeadLetterDatabaseQueue;

class UniqueDeadLetterDatabaseQueue extends DeadLetterDatabaseQueue
{
    protected function doCreateItem($data)
    {
        if ($existingItemId = $this->getExistingItemId($data)) {
            return $existingItemId;
        }

        return parent::doCreateItem($data);
    }

    protected function getExistingItemId($data): ?string
    {
        /** @var StatementInterface $stmt */
        $stmt = $this->connection->select(static::TABLE_NAME, 'q')
            ->fields('q', [
                'item_id',
            ])
            ->condition('data', serialize($data))
            ->condition('name', $this->name)
            ->condition('expire', 0)
            ->execute();

        return $stmt->fetch(\PDO::FETCH_COLUMN) ?: null;
    }
}
