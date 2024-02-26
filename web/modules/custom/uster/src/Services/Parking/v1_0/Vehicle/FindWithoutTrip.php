<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Services\Parking\v1_0\VehicleBase;

/**
 * Provides a FindWithoutTrip class.
 */
final class FindWithoutTrip extends VehicleBase {

  /**
   * Find vehicle without date.
   */
  public function __invoke(\DateTimeImmutable $date): array {
    return $this->repository->findWithoutTrip($date);
  }

}
