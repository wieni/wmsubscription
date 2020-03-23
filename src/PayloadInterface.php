<?php

namespace Drupal\wmsubscription;

interface PayloadInterface
{
    public function getEmail(): string;
}
