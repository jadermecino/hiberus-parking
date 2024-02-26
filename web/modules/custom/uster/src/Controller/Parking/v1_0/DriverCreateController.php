<?php

namespace Drupal\uster\Controller\Parking\v1_0;

use Drupal\Core\Controller\ControllerBase;
use Drupal\uster\Form\Parking\v1_0\DriverCreateForm;

/**
 * Provides a DriverCreateController class.
 *
 * @codeCoverageIgnore
 */
class DriverCreateController extends ControllerBase {

  /**
   * Build the identity config form instance add form.
   */
  public function __invoke(): array {
    return $this->formBuilder()
      ->getForm(DriverCreateForm::class);
  }

}
