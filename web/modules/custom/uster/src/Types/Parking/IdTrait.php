<?php

declare(strict_types=1);

namespace Drupal\uster\Types\Parking;

/**
 * Provides an 'IdTrait' class.
 *
 * @codeCoverageIgnore
 */
trait IdTrait {

  protected ?int $id = NULL;

  /**
   * Get id.
   *
   * @return int|null
   */
  public function id(): ?int {
    return $this->id;
  }

  /**
   * Set id.
   *
   * @param int $id
   */
  public function setId(int $id): void {
    $this->id = $id;
  }

}
