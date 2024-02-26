<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0;

use Drupal\uster\Repository\Parking\v1_0\DriverRepository;

/**
 * Provides a DriverBase class.
 */
abstract class DriverBase {

  /**
   * Constructor.
   *
   * @param \Drupal\uster\Repository\Parking\v1_0\DriverRepository $repository
   */
  public function __construct(protected DriverRepository $repository) {}

}
