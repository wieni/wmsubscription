<?php

namespace Drupal\wmsubscription\QueueDatabase;

use Drupal\Core\Database\StatementInterface;
use Drupal\Core\Queue\DatabaseQueue;

class UniqueSubscriptionQueue extends DatabaseQueue
{
    protected function doCreateItem($data)
    {
        if ($existingItemId = $this->hasItem($data)) {
            return $existingItemId;
        }
        return parent::doCreateItem($data);
    }

    public function hasItem($data)
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

        return $stmt->fetch(\PDO::FETCH_COLUMN) ?: false;
    }
}
