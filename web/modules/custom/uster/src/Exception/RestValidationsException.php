<?php

declare(strict_types=1);

namespace Drupal\uster\Exception;

class RestValidationsException extends UsterBaseException {

  /**
   * {@inheritdoc}
   */
  public function getStatusCode(): int {
    return 422;
  }

}
