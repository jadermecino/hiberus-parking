<?php

declare(strict_types=1);

namespace Drupal\uster\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Provides a 'UsterBaseExceptionInterface' interface.
 */
interface UsterBaseExceptionInterface extends HttpExceptionInterface {

  /**
   * {@inheritdoc}
   */
  public function getStatusCode(): int;

  /**
   * {@inheritdoc}
   */
  public function getHeaders(): array;

}
