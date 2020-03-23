<?php

namespace Drupal\wmsubscription\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * @Annotation
 */
class SubscriptionTool extends Plugin
{
    /** @var string */
    protected $label;

    public function getLabel()
    {
        return $this->definition['label'];
    }
}
