<?php

namespace Drupal\wmsubscription\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MessagesForm implements FormInterface, ContainerInjectionInterface
{
    use StringTranslationTrait;

    /** @var ConfigFactoryInterface */
    protected $configFactory;
    /** @var MessengerInterface */
    protected $messenger;

    public static function create(ContainerInterface $container)
    {
        $instance = new static;
        $instance->configFactory = $container->get('config.factory');
        $instance->messenger = $container->get('messenger');

        return $instance;
    }

    public function getFormId()
    {
        return 'wmsubscription_messages';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->configFactory->get('wmsubscription.messages');

        $messages = [
            'success' => $this->t('Success'),
            'already_subscribed' => $this->t('Already subscribed'),
            'email_unconfirmed' => $this->t('Already subscribed, but not yet confirmed'),
            'email_invalid' => $this->t('Email address is invalid'),
            'email_rejected' => $this->t('Email address is rejected'),
            'validation_failed' => $this->t('Validation failed'),
            'error_generic' => $this->t('Unknown error'),
        ];

        $form['messages'] = [
            '#type' => 'container',
            '#tree' => true,
            '#description' => 'The confirmation and error messages to be shown to the user',
        ];

        foreach ($messages as $key => $title) {
            $form['messages'][$key] = [
                '#type' => 'textfield',
                '#title' => $title,
                '#default_value' => $config->get($key),
                '#required' => 'true',
            ];
        }

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $config = $this->configFactory->getEditable('wmsubscription.messages');

        foreach ($form_state->getValue('messages') as $key => $message) {
            $config->set($key, $message);
        }

        $config->save();
        $this->messenger->addStatus('Successfully updated messages.');
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }
}
