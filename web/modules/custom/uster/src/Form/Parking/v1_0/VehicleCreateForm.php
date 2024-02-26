<?php

namespace Drupal\uster\Form\Parking\v1_0;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Create;
use Drupal\uster\Services\Parking\v1_0\Vehicle\FindByPlate;
use Drupal\uster\Types\Parking\v1_0\Vehicle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class VehicleCreateForm
 */
class VehicleCreateForm extends FormBase {

  /**
   * Constructor.
   */
  public function __construct(
    protected Create $creator,
    protected FindByPlate $finder
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('uster.services.parking.v1_0.vehicle.create'),
      $container->get('uster.services.parking.v1_0.vehicle.find_by_plate'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'uster_vehicle_create_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['brand'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Brand'),
      '#description' => $this->t('Vehicle brand.'),
      '#required' => TRUE,
    ];

    $form['model'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Model'),
      '#description' => $this->t('Vehicle model.'),
      '#required' => TRUE,
    ];

    $form['plate'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Plate'),
      '#description' => $this->t('Vehicle plate.'),
      '#required' => TRUE,
    ];

    $form['licence_required'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Licence required'),
      '#maxlength' => 1,
      '#description' => $this->t('Vehicle licence required.'),
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
    $vehicle = $this->createVehicle($form_state);
    try {
      $this->finder->__invoke($vehicle);
      $form_state->setErrorByName('plate', $this->t('Select another plate.'));
    }
    catch (NotFoundException) {}
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $vehicle = $this->createVehicle($form_state);
    $vehicle = $this->creator->__invoke($vehicle);
    $this->messenger()->addMessage(
      $this->t('Vehicle %brand %model %plate with ID: %id created.',
        [
          '%brand' => $vehicle->brand,
          '%model' => $vehicle->model,
          '%plate' => $vehicle->plate,
          '%id' => $vehicle->id(),
        ]
      ));
    $form_state->setRedirect('view.listado_de_vehiculos.page_1');
  }

  /**
   * Create vehicle object.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Vehicle
   */
  protected function createVehicle(FormStateInterface $form_state): Vehicle {
    $brand = $form_state->getValue('brand');
    $model = $form_state->getValue('model');
    $plate = $form_state->getValue('plate');
    $licence = $form_state->getValue('licence_required');

    return new Vehicle($brand, $model, $plate, $licence);
  }

}
