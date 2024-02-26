<?php

namespace Drupal\uster\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the vehicles entity class.
 *
 * @ContentEntityType(
 *   id = "vehicles",
 *   label = @Translation("Vehicles"),
 *   base_table = "vehicles",
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   fieldable = FALSE,
 *   links = {
 *     "canonical" = "/entity/vehicles/{vehicles}",
 *     "create" = "/entity/vehicles",
 *   },
 * )
 */

class Vehicles extends ContentEntityBase implements VehiclesInterface {

  /**
   * {@inheritdoc}
   */
  public function licenceRequired(): string {
    return $this->get('licence_required')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function brand(): string {
    return $this->get('brand')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function model(): string {
    return $this->get('model')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function plate(): string {
    return $this->get('plate')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the driver entity.'))
      ->setReadOnly(TRUE);

    $fields['brand'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Brand'))
      ->setDescription(t("The vehicles' brand."))
      ->setRequired(TRUE);

    $fields['model'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Model'))
      ->setDescription(t("The vehicles' model."))
      ->setRequired(TRUE);

    $fields['plate'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Plate'))
      ->setDescription(t("The vehicles' plate."))
      ->setRequired(TRUE);

    $fields['licence_required'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Licence required'))
      ->setDescription(t("The vehicles' licence required."))
      ->setRequired(TRUE)
      ->setSetting('max_length', 1);

    return $fields;
  }

}
