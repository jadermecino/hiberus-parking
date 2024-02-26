<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Trip;

use Drupal\uster\Services\Parking\v1_0\TripBase;

/**
 * Provides a TripList class.
 */
final class TripList extends TripBase {

  /**
   * Trip list.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function __invoke(): array {
    return $this->repository->findAll();
  }

}
