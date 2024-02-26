<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a VehicleRepository interface.
 */
interface VehicleRepository {

  /**
   * Find vehicle.
   *
   * @param string $id
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Vehicle
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function find(string $id): Vehicle;

  /**
   * Retrieve vehicle list.
   *
   * @return array
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function findAll(): array;

  /**
   * Find vehicle by plate.
   *
   * @param string $plate
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Vehicle
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function findByPlate(string $plate): Vehicle;

  /**
   * Find vehicles without trip.
   *
   * @param \DateTimeImmutable $date
   *
   * @return array
   */
  public function findWithoutTrip(\DateTimeImmutable $date): array;

  /**
   * Create vehicle.
   *
   * @param \Drupal\uster\Types\Parking\v1_0\Vehicle $vehicle
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Vehicle
   */
  public function create(Vehicle $vehicle): Vehicle;

  /**
   * Delete vehicle.
   *
   * @param string $id
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function delete(string $id): void;

}
