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
 *   id = "drivers",
 *   label = @Translation("Drivers"),
 *   base_table = "drivers",
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   fieldable = FALSE,
 *   links = {
 *     "canonical" = "/entity/drivers/{drivers}",
 *     "create" = "/entity/drivers",
 *   },
 * )
 */

class Drivers extends ContentEntityBase implements DriversInterface {

  /**
   * {@inheritdoc}
   */
  public function licence(): string {
    return $this->get('licence')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function name(): string {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function surname(): string {
    return $this->get('surname')->value;
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t("Driver's name."))
      ->setRequired(TRUE);

    $fields['surname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Surname'))
      ->setDescription(t("Driver's surname."))
      ->setRequired(TRUE);

    $fields['licence'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Licence'))
      ->setDescription(t("Driver's licence."))
      ->setSetting('max_length', 1)
      ->setRequired(TRUE);

    return $fields;
  }

}
