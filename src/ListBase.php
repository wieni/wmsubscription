<?php

namespace Drupal\wmsubscription;

abstract class ListBase implements ListInterface
{
    /** @var string */
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function label(): string
    {
        return $this->getId();
    }
}
