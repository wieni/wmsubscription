<?php

namespace Drupal\wmsubscription;

interface ListInterface
{
    public function getId(): string;

    public function label(): string;
}
