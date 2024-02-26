<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Services\Parking\v1_0\DriverBase;
use Drupal\uster\Types\Parking\v1_0\Driver;

/**
 * Provides a FindByNames class.
 */
final class FindByNames extends DriverBase {

  /**
   * Find driver by names.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $name, string $surname): Driver {
    return $this->repository->findByNames($name, $surname);
  }

}
