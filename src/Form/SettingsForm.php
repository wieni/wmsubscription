<?php

namespace Drupal\wmsubscription\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\wmsubscription\SubscriptionToolManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsForm implements FormInterface, ContainerInjectionInterface
{
    use StringTranslationTrait;

    /** @var ConfigFactoryInterface */
    protected $configFactory;
    /** @var MessengerInterface */
    protected $messenger;
    /** @var SubscriptionToolManager */
    protected $subscriptionTools;

    public static function create(ContainerInterface $container)
    {
        $instance = new static;
        $instance->configFactory = $container->get('config.factory');
        $instance->messenger = $container->get('messenger');
        $instance->subscriptionTools = $container->get('plugin.manager.wmsubscription');

        return $instance;
    }

    public function getFormId()
    {
        return 'wmsubscription_settings';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->configFactory->get('wmsubscription.settings');

        $form['manager'] = [
            '#type' => 'select',
            '#title' => 'Manager',
            '#description' => 'The service to use for managing subscriptions.',
            '#default_value' => $config->get('manager'),
            '#options' => [
                'wmsubscription.manager.direct' => 'Direct',
                'wmsubscription.manager.queued' => 'Queued',
            ],
            '#required' => 'true',
        ];

        $form['tool'] = [
            '#type' => 'select',
            '#title' => 'Provider',
            '#description' => 'The provider to submit subscriptions to.',
            '#default_value' => $config->get('tool'),
            '#options' => array_reduce(
                $this->subscriptionTools->getDefinitions(),
                static function (array $options, array $definition) {
                    $options[$definition['id']] = $definition['label'];
                    return $options;
                },
                []
            ),
            '#required' => 'true',
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $config = $this->configFactory->getEditable('wmsubscription.settings');
        $config->set('manager', $form_state->getValue('manager'));
        $config->set('tool', $form_state->getValue('tool'));
        $config->save();

        $this->messenger->addStatus('Successfully updated settings.');
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }
}
