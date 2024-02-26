<?php

declare(strict_types=1);

namespace Drupal\uster\Services\Parking\v1_0\Driver;

use Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException;
use Drupal\uster\Exception\Parking\v1_0\NotFoundException;
use Drupal\uster\Repository\Parking\v1_0\DriverRepository;
use Drupal\uster\Services\Parking\v1_0\DriverBase;
use Drupal\uster\Types\Parking\v1_0\Driver;

/**
 * Provides a Create class.
 */
final class Create extends DriverBase {

  /**
   * Constructor.
   */
  public function __construct(
    DriverRepository $repository,
    private readonly FindByNames $finder,
  ) {
    parent::__construct($repository);
  }

  /**
   * Create driver.
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   */
  public function __invoke(Driver $driver): Driver {
    try {
      $this->finder->__invoke($driver->name, $driver->surname);
      throw new AlreadyExistsException();
    }
    catch (NotFoundException) {
      return $this->repository->create($driver);
    }
  }

}
