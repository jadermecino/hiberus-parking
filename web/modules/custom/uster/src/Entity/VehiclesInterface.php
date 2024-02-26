<?php

namespace Drupal\uster\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface VehiclesInterface extends ContentEntityInterface {

  /**
   * Retrieve the vehicles' brand.
   *
   * @return string
   */
  public function brand(): string;

  /**
   * Retrieve the vehicles' model.
   *
   * @return string
   */
  public function model(): string;

  /**
   * Retrieve the vehicles' plate.
   *
   * @return string
   */
  public function plate(): string;

  /**
   * Retrieve the vehicles' licence required.
   *
   * @return string
   */
  public function licenceRequired(): string;

}
