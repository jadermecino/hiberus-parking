<?php

declare(strict_types=1);

namespace Drupal\uster\Types\Parking\v1_0;

use Drupal\uster\Types\Parking\IdTrait;

/**
 * Provides a 'Trip' class.
 *
 * @codeCoverageIgnore
 */
class Trip {

  use IdTrait;

  /**
   * Constructs a new 'Trip' object.
   */
  public function __construct(
    public readonly Vehicle $vehicle,
    public readonly Driver $driver,
    public readonly \DateTimeImmutable $date
  ) {}

  public function toArray(): array {
    return [
      'vehicle' => $this->vehicle->toArray(),
      'driver' => $this->driver->toArray(),
      'date' => $this->date->format('Y-m-d'),
    ];
  }

}
