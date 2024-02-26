<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Trip;

use Drupal\uster\Services\Parking\v1_0\TripBase;
use Drupal\uster\Types\Parking\v1_0\Trip;

/**
 * Provides a Find class.
 */
final class Find extends TripBase {

  /**
   * Find driver.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $id): Trip {
    return $this->repository->find($id);
  }

}
