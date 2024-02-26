<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Services\Parking\v1_0\VehicleBase;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a Find class.
 */
final class Find extends VehicleBase {

  /**
   * Find vehicle.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $id): Vehicle {
    return $this->repository->find($id);
  }

}
