<?php

namespace Drupal\uster\Form\Parking\v1_0;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Services\Parking\v1_0\Driver\Create;
use Drupal\uster\Services\Parking\v1_0\Driver\FindByNames;
use Drupal\uster\Types\Parking\v1_0\Driver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DriverCreateForm
 */
class DriverCreateForm extends FormBase {

  /**
   * Constructor.
   */
  public function __construct(
    protected Create $creator,
    protected FindByNames $finder
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('uster.services.parking.v1_0.driver.create'),
      $container->get('uster.services.parking.v1_0.driver.find_by_names'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'uster_driver_create_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Driver name.'),
      '#required' => TRUE,
    ];

    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#description' => $this->t('Driver surname.'),
      '#required' => TRUE,
    ];

    $form['licence'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Licence'),
      '#maxlength' => 1,
      '#description' => $this->t('Driver licence.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $name = $form_state->getValue('name');
    $surname = $form_state->getValue('surname');
    try {
      $this->finder->__invoke($name, $surname);
      $form_state->setErrorByName('name', $this->t('Select another name.'));
      $form_state->setErrorByName('surname', $this->t('Select another surname.'));
    }
    catch (NotFoundException) {}
  }


  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $name = $form_state->getValue('name');
    $surname = $form_state->getValue('surname');
    $licence = $form_state->getValue('licence');
    $driver = new Driver($name, $surname, $licence);
    $driver = $this->creator->__invoke($driver);
    $this->messenger()->addMessage(
      $this->t('Driver %name %surname with ID: %id created.',
        [
          '%name' => $driver->name,
          '%surname' => $driver->surname,
          '%id' => $driver->id(),
        ]
      ));
    $form_state->setRedirect('view.listado_de_conductores.page_1');
  }

}
