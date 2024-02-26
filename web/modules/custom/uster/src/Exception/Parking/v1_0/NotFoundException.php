<?php

declare(strict_types=1);

namespace Drupal\uster\Exception\Parking\v1_0;

use Drupal\uster\Exception\UsterBaseException;

/**
 * Provides a 'NotFoundException' class.
 *
 * @codeCoverageIgnore
 */
class NotFoundException extends UsterBaseException {

  /**
   * {@inheritdoc}
   */
  public function getStatusCode(): int {
    return 404;
  }

}
