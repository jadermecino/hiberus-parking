<?php

namespace Drupal\uster\Form\Parking\v1_0;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\uster\Entity\VehiclesInterface;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Delete;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'VehicleDeleteForm' class.
 *
 * @codeCoverageIgnore
 */
class VehicleDeleteForm extends ConfirmFormBase {

  protected ?VehiclesInterface $vehicle = NULL;

  /**
   * Constructor.
   */
  public function __construct(protected readonly Delete $deleter) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static($container->get('uster.services.parking.v1_0.vehicle.delete'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    ?VehiclesInterface $vehicle = NULL
  ): array {
    $this->vehicle = $vehicle;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion(): TranslatableMarkup {
    return $this->t(
      'Do you want to delete %brand %model with plate %plate Vehicle?',
      [
        '%brand' => $this->vehicle->brand(),
        '%model' => $this->vehicle->model(),
        '%plate' => $this->vehicle->plate(),
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url {
    return new Url('view.listado_de_vehiculos.page_1');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "uster_vehicle_delete_form";
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->deleter->__invoke($this->vehicle->id());
    $this->messenger()
      ->addMessage($this->t('Vehicle @brand @model with plate @plate and id: @id: deleted',
        [
          '@brand' => $this->vehicle->brand(),
          '@model' => $this->vehicle->model(),
          '@plate' => $this->vehicle->plate(),
          '@id' => $this->vehicle->id(),
        ]
      ));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
