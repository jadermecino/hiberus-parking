<?php

declare(strict_types=1);

namespace Drupal\uster\Repository\Parking\v1_0;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\uster\Entity\Drivers;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Types\Parking\v1_0\Driver;
use Drupal\uster\Types\Parking\v1_0\Vehicle;

/**
 * Class DrupalDriverRepository
 */
final class DrupalDriverRepository implements DriverRepository {

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
  public function find(string $id): Driver {
    /** @var \Drupal\uster\Entity\Drivers $entity */
    $entity = $this->getEntityStorage()->load($id);
    if ($entity) {
      $driver = $this->createDriverObject($entity);
      $driver->setId((int) $entity->id());

      return $driver;
    }

    throw new NotFoundException('The driver does not exist.');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function findAll(): array {
    $entities = $this->getEntityStorage()->loadMultiple();
    $drivers = [];

    /** @var \Drupal\uster\Entity\Drivers $entity */
    foreach ($entities as $entity) {
      $driver = $this->createDriverObject($entity);
      $driver->setId((int) $entity->id());
      $drivers[] = $driver->toArray();
    }

    return $drivers;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function findByNames(string $name, string $surname): Driver {
    $entities = $this->getEntityStorage()
      ->loadByProperties([
      'name' => $name,
      'surname' => $surname,
    ]);
    if ($entities) {
      /** @var \Drupal\uster\Entity\Drivers $entity */
      $entity = reset($entities);
      $driver = $this->createDriverObject($entity);
      $driver->setId((int) $entity->id());

      return $driver;
    }

    throw new NotFoundException('The driver does not exist.');
  }

  /**
   * {@inheritdoc}
   */
  public function findWithoutTrip(\DateTimeImmutable $date, Vehicle $vehicle): array {
    $query = $this->database
      ->select('drivers', 'd');
    $query->fields('d');
    $query->innerJoin(
      'vehicles',
      'v',
      "v.licence_required = d.licence"
    );
    $query->leftJoin(
      'trip',
      't',
      "t.driver = d.id AND t.date = :date",
      [
        ':date' => $date->format('Y-m-d')
      ]
    );
    $query->where('v.id = :vehicle_id', [ ':vehicle_id' => $vehicle->id() ]);
    $query->where('t.date IS NULL');
    $results = $query->execute()
      ->fetchAll(\PDO::FETCH_ASSOC);

    $drivers = [];
    foreach ($results as $driver_array) {
      $driver = new Driver($driver_array['name'], $driver_array['surname'], $driver_array['licence']);
      $driver->setId((int) $driver_array['id']);
      $drivers[] = $driver;
    }

    return $drivers;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(Driver $driver): Driver {
    $fields = [
      'name' => $driver->name,
      'surname' => $driver->surname,
      'licence' => $driver->licence(),
    ];
    /** @var \Drupal\uster\Entity\Drivers $entity */
    $entity = $this->getEntityStorage()
      ->create($fields);
    $entity->save();

    $driver_saved = $this->createDriverObject($entity);
    $driver_saved->setId((int) $entity->id());

    return $driver_saved;
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
        ->getStorage('drivers');
    }

    return $this->entity_storage;
  }

  /**
   * Create Driver object from entity.
   *
   * @param \Drupal\uster\Entity\Drivers $entity
   *
   * @return \Drupal\uster\Types\Parking\v1_0\Driver
   */
  private function createDriverObject(Drivers $entity): Driver {
    return new Driver($entity->name(), $entity->surname(), $entity->licence());
  }

}
