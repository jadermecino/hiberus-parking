<?php

declare(strict_types=1);

namespace Drupal\uster\Types\Parking\v1_0;

use Drupal\uster\Types\Parking\IdTrait;
use Drupal\uster\Types\Parking\LicenceTrait;

/**
 * Provides a 'Driver' class.
 *
 * @codeCoverageIgnore
 */
class Driver {

  use IdTrait, LicenceTrait;

  /**
   * Constructs a new 'Driver' object.
   *
   * @throws \InvalidArgumentException
   */
  public function __construct(
    public readonly string $name,
    public readonly string $surname,
    string $license
  ) {
    $this->ensureLicenceIsValid($license);
    $this->licence = $license;
  }

  /**
   * Retrieve the object as an array.
   *
   * @return array
   */
  public function toArray(): array {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'surname' => $this->surname,
      'licence' => $this->licence,
    ];
  }

}
