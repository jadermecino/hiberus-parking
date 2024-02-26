<?php

namespace Drupal\uster\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the drivers entity class.
 *
 * @ContentEntityType(
 *   id = "trip",
 *   label = @Translation("Trip"),
 *   base_table = "trip",
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   fieldable = FALSE,
 *   links = {
 *     "canonical" = "/entity/trip/{trip}",
 *     "create" = "/entity/trip",
 *   },
 * )
 */

class Trip extends ContentEntityBase implements TripInterface {

  /**
   * {@inheritdoc}
   */
  public function date(): string {
    return $this->get('date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function driver(): Drivers {
    return $this->get('driver')
      ->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function vehicle(): Vehicles {
    return $this->get('vehicle')
      ->entity;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the trip entity.'))
      ->setReadOnly(TRUE);

    $fields['vehicle'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Vehicle'))
      ->setDescription(t("Trip's vehicle."))
      ->setSetting('target_type', 'vehicles')
      ->setReadOnly(TRUE);

    $fields['driver'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Driver'))
      ->setDescription(t("Trip's driver."))
      ->setSetting('target_type', 'drivers')
      ->setReadOnly(TRUE);

    $fields['date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Date'))
      ->setDescription(t("Trip's date."))
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setRequired(TRUE);

    return $fields;
  }

}
