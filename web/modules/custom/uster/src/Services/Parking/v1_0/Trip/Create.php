<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Trip;

use Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Repository\Parking\v1_0\TripRepository;
use Drupal\uster\Services\Parking\v1_0\TripBase;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Find as VehicleFinder;
use Drupal\uster\Services\Parking\v1_0\Driver\Find as DriverFinder;
use Drupal\uster\Types\Parking\v1_0\Trip;

/**
 * Provides a Create class.
 */
final class Create extends TripBase {

  /**
   * Constructor.
   */
  public function __construct(
    TripRepository $repository,
    private readonly FindBy $finder,
    private readonly VehicleFinder $vehicle_finder,
    private readonly DriverFinder $driver_finder
  ) {
    parent::__construct($repository);
  }

  /**
   * Create trip.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   */
  public function __invoke(string $vehicle_id, string $driver_id, \DateTimeImmutable $date): Trip {
    $vehicle = $this->vehicle_finder->__invoke($vehicle_id);
    $driver = $this->driver_finder->__invoke($driver_id);
    $trip = new Trip($vehicle, $driver, $date);
    try {
      $this->finder->__invoke($trip);
      throw new AlreadyExistsException();
    }
    catch (NotFoundException) {
      return $this->repository->create($trip);
    }
  }

}
