<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\uster\Entity\Trip as TripEntity;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Types\Parking\v1_0\Driver;
use Drupal\uster\Types\Parking\v1_0\Trip;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Class DrupalTripRepository
 */
final class DrupalTripRepository implements TripRepository {

  private ?EntityStorageInterface $entity_storage = NULL;

  /**
   * Constructor.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager
  ) {}

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function find(string $id): Trip {
    /** @var \Drupal\uster\Entity\Trip $entity */
    $entity = $this->getEntityStorage()->load($id);
    if ($entity) {
      $trip = $this->createTripObject($entity);
      $trip->setId((int) $entity->id());

      return $trip;
    }

    throw new NotFoundException('The trip does not exist.');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Exception
   */
  public function findAll(): array {
    $entities = $this->getEntityStorage()->loadMultiple();
    $trips = [];

    /** @var \Drupal\uster\Entity\Trip $entity */
    foreach ($entities as $entity) {
      $trip = $this->createTripObject($entity);
      $trip->setId((int) $entity->id());
      $trips[] = $trip->toArray();
    }

    return $trips;
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Trip
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   * @throws \Exception
   */
  public function findBy(Trip $trip): Trip {
    $entities = $this->getEntityStorage()
      ->loadByProperties([
        'vehicle' => $trip->vehicle->id(),
        'driver' => $trip->driver->id(),
        'date' => $trip->date->format('Y-m-d'),
      ]);
    if ($entities) {
      /** @var \Drupal\uster\Entity\Trip $entity */
      $entity = reset($entities);
      $trip = $this->createTripObject($entity);
      $trip->setId((int) $entity->id());

      return $trip;
    }

    throw new NotFoundException('The driver does not exist.');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Exception
   */
  public function create(Trip $trip): Trip {
    $fields = [
      'vehicle' => $trip->vehicle->id(),
      'driver' => $trip->driver->id(),
      'date' => $trip->date->format('Y-m-d'),
    ];
    /** @var \Drupal\uster\Entity\Trip $entity */
    $entity = $this->getEntityStorage()
      ->create($fields);
    $entity->save();

    $trip_saved = $this->createTripObject($entity);
    $trip_saved->setId((int) $entity->id());

    return $trip_saved;
  }

  /**
   * Retrieve entity storage.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getEntityStorage(): EntityStorageInterface {
    if (!$this->entity_storage) {
      $this->entity_storage = $this->entityTypeManager
        ->getStorage('trip');
    }

    return $this->entity_storage;
  }

  /**
   * Create trip object.
   *
   * @param \Drupal\uster\Entity\Trip $entity
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Trip
   *
   * @throws \Exception
   */
  private function createTripObject(TripEntity $entity): Trip {
    $driver_entity = $entity->driver();
    $driver = new Driver(
      $driver_entity->name(),
      $driver_entity->surname(),
      $driver_entity->licence()
    );
    $driver->setId((int) $driver_entity->id());

    $vehicle_entity = $entity->vehicle();
    $vehicle = new Vehicle(
      $vehicle_entity->brand(),
      $vehicle_entity->model(),
      $vehicle_entity->plate(),
      $vehicle_entity->licenceRequired()
    );
    $vehicle->setId((int) $vehicle_entity->id());

    return new Trip($vehicle, $driver, new \DateTimeImmutable($entity->date()));
  }

}
