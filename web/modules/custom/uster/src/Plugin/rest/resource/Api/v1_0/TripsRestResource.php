<?php

namespace Drupal\uster\Plugin\rest\resource\Api\v1_0;

use Drupal\rest\Annotation\RestResource;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\uster\Services\Parking\v1_0\Trip\TripList;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a 'TripsRestResource' class.
 *
 * @RestResource(
 *  id = "uster_api_v1_0_parking_trips_rest_resource",
 *  label = @Translation("Parking Trip list v1.0"),
 *  uri_paths = {
 *    "canonical" = "/api/v1.0/parking/trips"
 *  }
 * )
 * @codeCoverageIgnore
 */
class TripsRestResource extends ResourceBase {

  /**
   * Constructor.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    array $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected TripList $list
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
      $container->get('uster.services.parking.v1_0.trip.list')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\rest\ResourceResponse
   *
   * @throws \Drupal\uster\Exception\Parking\v1_0\ListNotFoundException
   */
  public function get(Request $request): ResourceResponse {
    return new ResourceResponse($this->list->__invoke());
  }

}
