<?php

declare(strict_types=1);

namespace Drupal\uster\Plugin\rest\resource;

use Drupal\Component\Serialization\Json;
use Drupal\uster\Exception\RestValidationsException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides a 'ResourceTrait' trait
 *
 * @codeCoverageIgnore
 */
trait ResourceTrait {

  protected ?ValidatorInterface $validator;

  /**
   * Retrieve validator.
   */
  protected function validator(): ValidatorInterface {
    return $this->validator ?? ($this->validator = \Drupal::service('symfony.validator'));
  }

  /**
   * Ensure validations are correct
   *
   * @param array $inputs
   * @param \Symfony\Component\Validator\Constraints\Collection $constrains
   *
   * @throws \Drupal\uster\Exception\RestValidationsException
   */
  protected function ensureArrayValidationsAreCorrect(
    array $inputs,
    Collection $constrains
  ): void {
    $errors = $this->validator->validate($inputs, $constrains);

    if (count($errors) > 0) {
      $errors = $this->getConstrainsErrors($errors);
      throw new RestValidationsException(Json::encode($errors));
    }
  }

  /**
   * Retrieve constrains errors.
   */
  protected function getConstrainsErrors(ConstraintViolationListInterface $errors_list): array {
    $errors = [];
    foreach ($errors_list as $error) {
      $errors[] = [
        'property' => $error->getPropertyPath(),
        'value' => $error->getInvalidValue(),
        'message' => $error->getMessage(),
      ];
    }

    return $errors;
  }

}
