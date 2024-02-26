<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Trip;

use Drupal\uster\Services\Parking\v1_0\TripBase;
use Drupal\uster\Types\Parking\v1_0\Trip;

/**
 * Provides a FindByVehicleAndDriver class.
 */
final class FindBy extends TripBase {

  /**
   * Find trip by vehicle and driver.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(Trip $trip): Trip {
    return $this->repository->findBy($trip);
  }

}
