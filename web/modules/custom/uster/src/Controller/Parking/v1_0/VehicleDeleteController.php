<?php

namespace Drupal\uster\Controller\Parking\v1_0;

use Drupal\Core\Controller\ControllerBase;
use Drupal\uster\Entity\VehiclesInterface;
use Drupal\uster\Form\Parking\v1_0\VehicleDeleteForm;

/**
 * Provides a VehicleDeleteController class.
 *
 * @codeCoverageIgnore
 */
class VehicleDeleteController extends ControllerBase {

  /**
   * Build the identity config form instance add form.
   */
  public function __invoke(VehiclesInterface $vehicle): array {
    return $this->formBuilder()
      ->getForm(VehicleDeleteForm::class, $vehicle);
  }

}
