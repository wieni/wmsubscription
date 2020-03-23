<?php

namespace Drupal\wmsubscription\Exception;

class ValidationFailedException extends SubscriptionException
{
    public const MESSAGE_KEY = 'validation_failed';

    /**
     * An array of validation errors
     * @var array
     */
    protected $errors;

    public function __construct(string $message = '', array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }
}
