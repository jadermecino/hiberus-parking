<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Services\Parking\v1_0\VehicleBase;

/**
 * Provides a Delete class.
 */
final class VehicleList extends VehicleBase {

  /**
   * Vehicle list.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function __invoke(): array {
    return $this->repository->findAll();
  }

}
