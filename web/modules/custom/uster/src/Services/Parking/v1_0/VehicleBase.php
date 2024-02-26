<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0;

use Drupal\uster\Repository\Parking\v1_0\VehicleRepository;

/**
 * Provides a VehicleBase class.
 */
abstract class VehicleBase {

  /**
   * Constructor.
   *
   * @param \Drupal\uster\Repository\Parking\v1_0\VehicleRepository $repository
   */
  public function __construct(protected VehicleRepository $repository) {}

}
