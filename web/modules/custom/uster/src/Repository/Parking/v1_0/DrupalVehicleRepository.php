<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\uster\Entity\Vehicles;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Class DrupalVehicleRepository
 */
final class DrupalVehicleRepository implements VehicleRepository {

  private ?EntityStorageInterface $entity_storage = NULL;

  /**
   * Constructor.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly Connection $database
  ) {}

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function find(string $id): Vehicle {
    /** @var \Drupal\uster\Entity\Vehicles $entity */
    $entity = $this->getEntityStorage()->load($id);
    if ($entity) {
      $vehicle = $this->createVehicleObject($entity);
      $vehicle->setId((int) $entity->id());

      return $vehicle;
    }

    throw new NotFoundException('The vehicle does not exist.');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function findAll(): array {
    $entities = $this->getEntityStorage()->loadMultiple();
    $vehicles = [];

    /** @var \Drupal\uster\Entity\Vehicles $entity */
    foreach ($entities as $entity) {
      $driver = $this->createVehicleObject($entity);
      $driver->setId((int) $entity->id());
      $vehicles[] = $driver->toArray();
    }

    return $vehicles;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function findByPlate(string $plate): Vehicle {
    $entities = $this->getEntityStorage()
      ->loadByProperties([
        'plate' => $plate,
      ]);
    if ($entities) {
      $entity = reset($entities);
      $vehicle = $this->createVehicleObject($entity);
      $vehicle->setId((int) $entity->id());

      return $vehicle;
    }

    throw new NotFoundException('The vehicle does not exist.');
  }

  /**
   * {@inheritdoc}
   */
  public function findWithoutTrip(\DateTimeImmutable $date): array {
    $query = $this->database
      ->select('vehicles', 'v');
    $query->fields('v');
    $query->leftJoin(
      'trip',
      't',
      "v.id = t.vehicle AND t.date = :date",
      [
        ':date' => $date->format('Y-m-d')
      ]
    );
    $query->where('t.date IS NULL');
    $results = $query->execute()
      ->fetchAll(\PDO::FETCH_ASSOC);

    $vehicles = [];
    foreach ($results as $vehicle_array) {
      $vehicle = new Vehicle(
        $vehicle_array['brand'],
        $vehicle_array['model'],
        $vehicle_array['plate'],
        $vehicle_array['licence_required']
      );
      $vehicle->setId((int) $vehicle_array['id']);
      $vehicles[] = $vehicle;
    }

    return $vehicles;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(Vehicle $vehicle): Vehicle {
    $fields = [
      'brand' => $vehicle->brand,
      'model' => $vehicle->model,
      'plate' => $vehicle->plate,
      'licence_required' => $vehicle->licenceRequired(),
    ];
    /** @var \Drupal\uster\Entity\Vehicles $entity */
    $entity = $this->getEntityStorage()
      ->create($fields);
    $entity->save();

    $vehicle_saved = $this->createVehicleObject($entity);
    $vehicle_saved->setId((int) $entity->id());

    return $vehicle_saved;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function delete(string $id): void {
    $entity = $this->getEntityStorage()->load($id);
    if ($entity) {
      $entity->delete();
    } else {
      throw new NotFoundException('The driver does not exist.');
    }
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
        ->getStorage('vehicles');
    }

    return $this->entity_storage;
  }

  /**
   * Generate vehicle object.
   *
   * @param \Drupal\uster\Entity\Vehicles $entity
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Vehicle
   */
  private function createVehicleObject(Vehicles $entity): Vehicle {
    return new Vehicle($entity->brand(), $entity->model(), $entity->plate(), $entity->licenceRequired());
  }

}
