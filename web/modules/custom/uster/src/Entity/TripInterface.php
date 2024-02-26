<?php

namespace Drupal\uster\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface TripInterface extends ContentEntityInterface {

  /**
   * Get date.
   *
   * @return string
   */
  public function date(): string;

  /**
   * Get driver entity.
   *
   * @return \Drupal\uster\Entity\Drivers
   */
  public function driver(): Drivers;

  /**
   * Get vehicle entity.
   *
   * @return \Drupal\uster\Entity\Vehicles
   */
  public function vehicle(): Vehicles;

}
