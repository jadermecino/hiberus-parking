<?php

declare(strict_types=1);

namespace Drupal\uster\Types\Parking;

/**
 * Provides an 'LicenceTrait' class.
 *
 * @codeCoverageIgnore
 */
trait LicenceTrait {

  protected string $licence;

  /**
   * @param string $license
   *
   * @throws \InvalidArgumentException
   */
  protected function ensureLicenceIsValid(string $license): void {
    if (strlen($license) !== 1) {
      throw new \InvalidArgumentException('Invalid license');
    }
  }

  /**
   * Get licence.
   *
   * @return string
   */
  public function licence(): string {
    return $this->licence;
  }

}
