<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Repository\Parking\v1_0\VehicleRepository;
use Drupal\uster\Services\Parking\v1_0\VehicleBase;

/**
 * Provides a Delete class.
 */
final class Delete extends VehicleBase {

  /**
   * Constructor.
   */
  public function __construct(
    VehicleRepository $repository,
    private readonly Find $finder
  ) {
    parent::__construct($repository);
  }

  /**
   * Delete vehicle.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function __invoke(string $id): void {
    $this->finder->__invoke($id);
    $this->repository->delete($id);
  }

}
