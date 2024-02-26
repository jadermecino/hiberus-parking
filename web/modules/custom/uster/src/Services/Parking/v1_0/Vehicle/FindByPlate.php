<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Services\Parking\v1_0\VehicleBase;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a FindByPlate class.
 */
final class FindByPlate extends VehicleBase {

  /**
   * Find vehicle by plate.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(Vehicle $vehicle): Vehicle {
    return $this->repository->findByPlate($vehicle->plate);
  }

}
