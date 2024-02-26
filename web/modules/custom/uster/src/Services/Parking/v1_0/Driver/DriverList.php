<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Services\Parking\v1_0\DriverBase;

/**
 * Provides a DriverList class.
 */
final class DriverList extends DriverBase {

  /**
   * Driver list.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function __invoke(): array {
    return $this->repository->findAll();
  }

}
