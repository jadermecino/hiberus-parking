<?php

namespace Drupal\uster\Controller\Parking\v1_0;

use Drupal\Core\Controller\ControllerBase;
use Drupal\uster\Entity\DriversInterface;
use Drupal\uster\Form\Parking\v1_0\DriverDeleteForm;

/**
 * Provides a DriverDeleteController class.
 *
 * @codeCoverageIgnore
 */
class DriverDeleteController extends ControllerBase {

  /**
   * Build the identity config form instance add form.
   */
  public function __invoke(DriversInterface $driver): array {
    return $this->formBuilder()
      ->getForm(DriverDeleteForm::class, $driver);
  }

}
