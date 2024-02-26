<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0;

use Drupal\uster\Repository\Parking\v1_0\TripRepository;

/**
 * Provides a TripBase class.
 */
abstract class TripBase {

  /**
   * Constructor.
   *
   * @param \Drupal\uster\Repository\Parking\v1_0\TripRepository $repository
   */
  public function __construct(protected TripRepository $repository) {}

}
