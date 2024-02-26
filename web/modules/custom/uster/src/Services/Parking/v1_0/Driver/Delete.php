<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Repository\Parking\v1_0\DriverRepository;
use Drupal\uster\Services\Parking\v1_0\DriverBase;

/**
 * Provides a Delete class.
 */
final class Delete extends DriverBase {

  /**
   * Constructor.
   */
  public function __construct(
    DriverRepository $repository,
    private readonly Find $finder
  ) {
    parent::__construct($repository);
  }

  /**
   * Delete driver.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $id): void {
    $this->finder->__invoke($id);
    $this->repository->delete($id);
  }

}
