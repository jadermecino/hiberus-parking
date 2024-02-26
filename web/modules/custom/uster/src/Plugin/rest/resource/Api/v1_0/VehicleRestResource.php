<?php

namespace Drupal\uster\Plugin\rest\resource\Api\v1_0;

use Drupal\rest\Annotation\RestResource;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\uster\Plugin\rest\resource\ResourceTrait;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Create;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Delete;
use Drupal\uster\Services\Parking\v1_0\Vehicle\Find;
use Drupal\uster\Types\Parking\v1_0\Vehicle;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides a 'VehicleRestResource' class.
 *
 * @RestResource(
 *  id = "uster_api_v1_0_parking_vehicle_rest_resource",
 *  label = @Translation("Parking Vehicle v1.0"),
 *  uri_paths = {
 *    "canonical" = "/api/v1.0/parking/vehicle/{id}",
 *    "create" = "/api/v1.0/parking/vehicle/"
 *  }
 * )
 *
 * @codeCoverageIgnore
 */
class VehicleRestResource extends ResourceBase {

  use ResourceTrait;

  /**
   * Constructor.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    array $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected Create $creator,
    protected Find $finder,
    protected Delete $deleter,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.channel.uster'),
      $container->get('uster.services.parking.v1_0.vehicle.create'),
      $container->get('uster.services.parking.v1_0.vehicle.find'),
      $container->get('uster.services.parking.v1_0.vehicle.delete')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @param string $id
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\rest\ResourceResponse
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function get(string $id, Request $request): ResourceResponse {
    return new ResourceResponse($this->finder->__invoke($id)->toArray());
  }

  /**
   * Responds to POST requests.
   *
   * @param array $data
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\AlreadyExistsException
   * @throws \Drupal\uster\Exception\RestValidationsException
   */
  public function post(array $data, Request $request): ModifiedResourceResponse {
    $this->ensureArrayValidationsAreCorrect($data, $this->postConstraints());
    $vehicle = new Vehicle($data['brand'], $data['model'], $data['plate'], $data['licence_required']);

    return new ModifiedResourceResponse($this->creator->__invoke($vehicle)->toArray());
  }

  /**
   * Responds to DELETE requests.
   *
   * @param string $id
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   */
  public function delete(string $id, Request $request): ModifiedResourceResponse {
    $this->deleter->__invoke($id);
    return new ModifiedResourceResponse([], 204);
  }

  /**
   * Assert patch validations.
   *
   * @return \Symfony\Component\Validator\Constraints\Collection
   */
  protected function postConstraints(): Assert\Collection {
    return new Assert\Collection([
      'fields' => [
        'brand' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Type(['type' => 'string']),
        ]),
        'model' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Type(['type' => 'string']),
        ]),
        'plate' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Type(['type' => 'string']),
        ]),
        'licence_required' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Type(['type' => 'string']),
          new Assert\Length(['min' => 1, 'max' => 1]),
        ]),
      ],
    ]);
  }

}
