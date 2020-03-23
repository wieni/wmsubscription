<?php

namespace Drupal\wmsubscription;

abstract class PayloadBase implements PayloadInterface
{
    /** @var string */
    protected $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
