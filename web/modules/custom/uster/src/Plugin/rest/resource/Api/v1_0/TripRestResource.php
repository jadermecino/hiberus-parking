<?php

namespace Drupal\uster\Plugin\rest\resource\Api\v1_0;

use Drupal\rest\Annotation\RestResource;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\uster\Plugin\rest\resource\ResourceTrait;
use Drupal\uster\Services\Parking\v1_0\Trip\Create;
use Drupal\uster\Services\Parking\v1_0\Trip\Find as TripFinder;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides a 'TripRestResource' class.
 *
 * @RestResource(
 *  id = "uster_api_v1_0_parking_trip_rest_resource",
 *  label = @Translation("Parking Trip v1.0"),
 *  uri_paths = {
 *    "canonical" = "/api/v1.0/parking/trip/{id}",
 *    "create" = "/api/v1.0/parking/trip/"
 *  }
 * )
 *
 * @codeCoverageIgnore
 */
class TripRestResource extends ResourceBase {

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
    protected TripFinder $trip_finder,
    protected Create $creator
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
      $container->get('uster.services.parking.v1_0.trip.find'),
      $container->get('uster.services.parking.v1_0.trip.create')
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
    return new ResourceResponse($this->trip_finder->__invoke($id)->toArray());
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
   * @throws \Drupal\uster\Exception\Parking\v1_0\NotFoundException
   * @throws \Exception
   */
  public function post(array $data, Request $request): ModifiedResourceResponse {
    $this->ensureArrayValidationsAreCorrect($data, $this->postConstraints());
    $date = new \DateTimeImmutable($data['date']);
    $trip = $this->creator->__invoke($data['vehicle'], $data['driver'], $date);

    return new ModifiedResourceResponse($trip->toArray());
  }

  /**
   * Assert patch validations.
   *
   * @return \Symfony\Component\Validator\Constraints\Collection
   */
  protected function postConstraints(): Assert\Collection {
    return new Assert\Collection([
      'fields' => [
        'vehicle' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Positive(),
        ]),
        'driver' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Positive(),
        ]),
        'date' => new Assert\Required([
          new Assert\NotBlank(),
          new Assert\Date(),
        ]),
      ],
    ]);
  }

}
