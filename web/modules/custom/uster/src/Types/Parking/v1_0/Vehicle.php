<?php

declare(strict_types=1);

namespace Drupal\uster\Types\Parking\v1_0;

use Drupal\uster\Types\Parking\IdTrait;
use Drupal\uster\Types\Parking\LicenceTrait;

/**
 * Provides a 'Vehicle' class.
 *
 * @codeCoverageIgnore
 */
class Vehicle {

  use IdTrait, LicenceTrait;

  /**
   * Constructs a new 'Vehicle' object.
   *
   * @throws \InvalidArgumentException
   */
  public function __construct(
    public readonly string $brand,
    public readonly string $model,
    public readonly string $plate,
    string $license_required
  ) {
    $this->ensureLicenceIsValid($license_required);
    $this->licence = $license_required;
  }

  /**
   * Licence required field.
   *
   * @return string
   */
  public function licenceRequired(): string {
    return $this->licence();
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'brand' => $this->brand,
      'model' => $this->model,
      'plate' => $this->plate,
      'licence_required' => $this->licenceRequired(),
    ];
  }

}
