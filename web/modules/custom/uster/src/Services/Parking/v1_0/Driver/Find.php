<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Services\Parking\v1_0\DriverBase;
use Drupal\uster\Types\Parking\v1_0\Driver;

/**
 * Provides a Find class.
 */
final class Find extends DriverBase {

  /**
   * Find driver.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $id): Driver {
    return $this->repository->find($id);
  }

}
