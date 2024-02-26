<?php

namespace Drupal\uster\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface DriversInterface extends ContentEntityInterface {

  /**
   * Retrieve driver's licence.
   *
   * @return string
   */
  public function licence(): string;

  /**
   * Retrieve driver's name.
   *
   * @return string
   */
  public function name(): string;

  /**
   * Retrieve driver's surname.
   *
   * @return string
   */
  public function surname(): string;

}
