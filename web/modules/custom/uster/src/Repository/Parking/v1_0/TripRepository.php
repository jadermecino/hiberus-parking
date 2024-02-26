<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\uster\Types\Parking\v1_0\Trip;

/**
 * Provides a TripRepository interface.
 */
interface TripRepository {

  /**
   * Find trip.
   *
   * @param string $id
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Trip
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function find(string $id): Trip;

  /**
   * Trip list.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function findAll(): array;

  /**
   * Find trip by vehicle and driver.
   *
   * @param \Drupal\uster\Types\Parking\v1_0\Trip $trip
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Trip
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function findBy(Trip $trip): Trip;

  /**
   * Create trip.
   *
   * @param \Drupal\uster\Types\Parking\v1_0\Trip $trip
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Trip
   */
  public function create(Trip $trip): Trip;

}
