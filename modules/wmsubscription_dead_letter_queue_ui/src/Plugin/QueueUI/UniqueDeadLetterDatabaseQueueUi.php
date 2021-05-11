<?php

namespace Drupal\wmsubscription_dead_letter_queue_ui\Plugin\QueueUI;

use Drupal\dead_letter_queue_ui\Plugin\QueueUI\DeadLetterDatabaseQueueUi;

/**
 * @QueueUI(
 *     id = "unique_dead_letter_database_queue",
 *     class_name = "UniqueDeadLetterDatabaseQueue"
 * )
 */
class UniqueDeadLetterDatabaseQueueUi extends DeadLetterDatabaseQueueUi
{
}
