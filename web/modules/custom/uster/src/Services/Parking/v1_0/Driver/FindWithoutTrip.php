<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Services\Parking\v1_0\DriverBase;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a FindWithoutTrip class.
 */
final class FindWithoutTrip extends DriverBase {

  /**
   * Find vehicle without date.
   */
  public function __invoke(\DateTimeImmutable $date, Vehicle $vehicle): array {
    return $this->repository->findWithoutTrip($date, $vehicle);
  }

}
