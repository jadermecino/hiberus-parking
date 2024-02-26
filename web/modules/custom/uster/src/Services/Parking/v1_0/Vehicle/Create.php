<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Vehicle;

use Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Repository\Parking\v1_0\VehicleRepository;
use Drupal\uster\Services\Parking\v1_0\VehicleBase;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a Create class.
 */
final class Create extends VehicleBase {

  /**
   * Constructor.
   */
  public function __construct(
    VehicleRepository $repository,
    private readonly FindByPlate $finder
  ) {
    parent::__construct($repository);
  }

  /**
   * Create vehicle.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   */
  public function __invoke(Vehicle $vehicle): Vehicle {
    try {
      $this->finder->__invoke($vehicle);
      throw new AlreadyExistsException();
    }
    catch (NotFoundException) {
      return $this->repository->create($vehicle);
    }
  }

}
