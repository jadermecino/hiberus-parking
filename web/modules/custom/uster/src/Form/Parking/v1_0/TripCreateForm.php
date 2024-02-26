<?php

namespace Drupal\uster\Form\Parking\v1_0;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException;
use Drupal\uster\Services\Parking\v1_0\Driver\FindWithoutTrip as DriverFindWithoutTrip;
use Drupal\uster\Services\Parking\v1_0\Trip\Create;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Find as VehicleFind;
use Drupal\uster\Services\Parking\v1_0\Vehicle\FindWithoutTrip as VehicleFindWithoutTrip;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TripCreateForm
 */
class TripCreateForm extends FormBase {

  /**
   * Constructor.
   */
  public function __construct(
    protected DriverFindWithoutTrip $driver_finder,
    protected Create $trip_creator,
    protected VehicleFind $vehicle_loader,
    protected VehicleFindWithoutTrip $vehicle_finder,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('uster.services.parking.v1_0.driver.find_without_trip'),
      $container->get('uster.services.parking.v1_0.trip.create'),
      $container->get('uster.services.parking.v1_0.vehicle.find'),
      $container->get('uster.services.parking.v1_0.vehicle.find_without_trip'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'uster_trip_create_form';
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['pages'] = [
      '#type' => 'dropbutton',
      '#dropbutton_type' => 'small',
      '#links' => [
        'create_driver' => [
          'title' => $this->t('Create driver.'),
          'url' => Url::fromRoute('uster.controllers.parking.v1_0.driver_create_form'),
        ],
        'create_vehicle' => [
          'title' => $this->t('Create vehicle.'),
          'url' => Url::fromRoute('uster.controllers.parking.v1_0.vehicle_create_form'),
        ],
      ],
    ];

    if ($form_state->has('page_num') && $form_state->get('page_num') === 2) {
      return $this->secondStepForm($form, $form_state);
    }

    if ($form_state->has('page_num') && $form_state->get('page_num') === 3) {
      return $this->thirdStepForm($form, $form_state);
    }

    return $this->firstStepForm($form, $form_state);
  }

  /**
   * First step form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function firstStepForm(array $form, FormStateInterface $form_state): array {
    $form_state->set('page_num', 1);

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('First step, select trip date'),
    ];

    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#default_value' => $form_state->getValue('date', ''),
      '#description' => $this->t('Trip date.'),
      '#date_date_format' => 'Y-m-d',
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Find vehicles'),
      '#submit' => ['::firstStepSubmit'],
      '#validate' => ['::firstStepValidate'],
    ];

    return $form;
  }

  /**
   * First step validation.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @throws \Exception
   */
  public function firstStepValidate(array &$form, FormStateInterface $form_state): void {
    $date = $form_state->getValue('date');
    $selected_date = new \DateTimeImmutable($date);
    $current_date = new \DateTimeImmutable('now');
    $current_date = $current_date->format('Y-m-d');

    if ($selected_date < $current_date) {
      $form_state->setErrorByName('date', $this->t('Date must be in the future.'));
    }

    $vehicles = $this->findVehicleByDate($form_state->getValue('date'));

    if(!$vehicles) {
      $form_state->setErrorByName('date', $this->t('No vehicles available for this date.'));
    }
  }

  /**
   * First step submit.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function firstStepSubmit(array &$form, FormStateInterface $form_state): void {
    $form_state->set('first_page_values', [
        'date' => $form_state->getValue('date'),
      ])
      ->set('page_num', 2)
      ->setRebuild();
  }

  /**
   * First step back.
   */
  public function firstStepBack(array &$form, FormStateInterface $form_state): void {
    $form_state
      ->setValues($form_state->get('first_page_values'))
      ->set('page_num', 1)
      ->setRebuild();
  }

  /**
   * Second step form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   * @throws \Exception
   */
  public function secondStepForm(array $form, FormStateInterface $form_state): array {
    $options = [];
    $vehicles = $this->findVehicleByDate($form_state->getValue('date'));

    foreach ($vehicles as $vehicle) {
      $options[$vehicle->id()] = $vehicle->plate . ' - ' . $vehicle->brand . ' ' . $vehicle->model;
    }

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('Second step, select vehicle'),
    ];

    $form['vehicle'] = [
      '#type' => 'select',
      '#title' => $this->t('Vehicle'),
      '#default_value' => $form_state->getValue('vehicle', ''),
      '#description' => $this->t('Select vehicle.'),
      '#options' => $options,
      '#empty_option' => $this->t('Select vehicle'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Select date'),
      '#submit' => ['::firstStepBack'],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Select driver'),
      '#submit' => ['::secondStepSubmit'],
      '#validate' => ['::secondStepValidate'],
    ];

    return $form;
  }

  /**
   * Second step validation.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @throws \Exception
   */
  public function secondStepValidate(array &$form, FormStateInterface $form_state): void {
    //
  }

  /**
   * Second step submit.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function secondStepSubmit(array &$form, FormStateInterface $form_state): void {
    $form_state->set('second_page_values', [
      'vehicle' => $form_state->getValue('vehicle'),
    ])
      ->set('page_num', 3)
      ->setRebuild();
  }

  /**
   * First step back.
   */
  public function secondStepBack(array &$form, FormStateInterface $form_state): void {
    $form_state
      ->setValues($form_state->get('second_page_values'))
      ->set('page_num', 2)
      ->setRebuild();
  }

  /**
   * Third step form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function thirdStepForm(array $form, FormStateInterface $form_state): array {
    $options = [];
    $vehicle_id = $form_state->getValue('vehicle');
    $vehicle = $this->vehicle_loader->__invoke($vehicle_id);
    $vehicle->setId($vehicle->id());

    $drivers = $this->driver_finder->__invoke(new \DateTimeImmutable('now'), $vehicle);
    foreach ($drivers as $driver) {
      $options[$driver->id()] = $driver->name . ' ' . $driver->surname;
    }

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('Third step, select driver'),
    ];

    $form['driver'] = [
      '#type' => 'select',
      '#title' => $this->t('Driver'),
      '#default_value' => $form_state->getValue('driver', ''),
      '#description' => $this->t('Select driver.'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Select vehicle'),
      '#submit' => ['::secondStepBack'],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Save trip'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    //
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $first_page_values = $form_state->get('first_page_values');
    $second_page_values = $form_state->get('second_page_values');
    $date = $first_page_values['date'];
    $vehicle = $second_page_values['vehicle'];
    $driver = $form_state->getValue('driver');
    try {
      $trip = $this->trip_creator->__invoke($vehicle, $driver, new \DateTimeImmutable($date));
      $this->messenger()
        ->addStatus($this->t('Trip created'));
      $this->messenger()
        ->addMessage($this->t('Driver: %driver, Vehicle: %vehicle, Date: %date with ID: %id', [
          '%driver' => $trip->driver->name . ' ' . $trip->driver->surname,
          '%vehicle' => $trip->vehicle->plate,
          '%date' => $date,
          '%id' => $trip->id(),
        ]));
    }
    catch (AlreadyExistsException) {
      $this->messenger()
        ->addStatus($this->t('Trip already exists, please re-select date.'));
      $form_state->setRedirect('uster.controllers.parking.v1_0.trip_create_form');
    }
  }

  /**
   * Find vehicle by date.
   *
   * @param string $date
   *
   * @return array
   * @throws \Exception
   */
  protected function findVehicleByDate(string $date) : array {
    $date = new \DateTimeImmutable($date);

    return $this->vehicle_finder->__invoke($date);
  }

}
