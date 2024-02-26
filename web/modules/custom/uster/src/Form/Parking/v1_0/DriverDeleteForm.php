<?php

namespace Drupal\uster\Form\Parking\v1_0;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\uster\Entity\DriversInterface;
use Drupal\uster\Services\Parking\v1_0\Driver\Delete;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'DriverDeleteForm' class.
 *
 * @codeCoverageIgnore
 */
class DriverDeleteForm extends ConfirmFormBase {

  protected ?DriversInterface $driver = NULL;

  /**
   * Constructor.
   */
  public function __construct(protected readonly Delete $deleter) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static($container->get('uster.services.parking.v1_0.driver.delete'));
  }

  /**
   * Retrieve driver id from parameters.
   */
  protected function retrieveDriverId(): DriversInterface {
    return $this->getRouteMatch()->getParameter('driver');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    ?DriversInterface $driver = NULL
  ): array {
    $this->driver = $driver;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion(): TranslatableMarkup {
    return $this->t(
      'Do you want to delete %name %surname Driver?',
      [
        '%name' => $this->driver->name(),
        '%surname' => $this->driver->surname(),
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url {
    return new Url('view.listado_de_conductores.page_1');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "uster_driver_delete_form";
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->deleter->__invoke($this->driver->id());
    $this->messenger()
      ->addMessage($this->t('Driver @name @surname with id: @driver_id: deleted',
        [
          '@name' => $this->driver->name(),
          '@surname' => $this->driver->surname(),
          '@driver_id' => $this->driver->id(),
        ]
      ));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
