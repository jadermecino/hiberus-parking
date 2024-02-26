<?php

declare(strict_types=1);

namespace Drupal\uster\Exception;

/**
 * Provides a 'UsterBaseException' class.
 *
 * @codeCoverageIgnore
 */
abstract class UsterBaseException extends \Exception implements UsterBaseExceptionInterface {

  /**
   * Constructor.
   */
  public function __construct(
    ?string $message = '',
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, $this->getStatusCode(), $previous);
  }

  /**
   * {@inheritdoc}
   */
  public function getHeaders(): array {
    return [];
  }

}
