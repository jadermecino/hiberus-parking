<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\uster\Types\Parking\v1_0\Driver;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Provides a DriverRepository interface.
 */
interface DriverRepository {

  /**
   * Retrieve driver.
   *
   * @param string $id
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Driver
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function find(string $id): Driver;

  /**
   * Retrieve driver list.
   *
   * @return array
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function findAll(): array;

  /**
   * Find driver by names.
   *
   * @param string $name
   * @param string $surname
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Driver
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function findByNames(string $name, string $surname): Driver;

  /**
   * Find drivers without trip.
   *
   * @param \DateTimeImmutable $date
   * @param \Drupal\uster\Types\Parking\v1_0\Vehicle $vehicle
   *
   * @return array
   */
  public function findWithoutTrip(\DateTimeImmutable $date, Vehicle $vehicle): array;

  /**
   * Create driver.
   *
   * @param \Drupal\uster\Types\Parking\v1_0\Driver $driver
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Driver
   */
  public function create(Driver $driver): Driver;

  /**
   * Delete driver.
   *
   * @param string $id
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function delete(String $id): void;

}
