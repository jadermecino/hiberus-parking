<?php

declare(strict_types=1);

namespace Drupal\uster\EventSubscriber;

use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
use Drupal\uster\Exception\UsterBaseExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Provides a 'ExceptionSubscriber' class.
 *
 * @codeCoverageIgnore
 */
class ExceptionSubscriber implements EventSubscriberInterface {

  protected ExceptionEvent $event;

  /**
   * Specifies the request formats this subscriber will respond to.
   */
  protected function getHandledFormats(): array {
    return ['json'];
  }

  /**
   * Specifies the priority of all listeners in this class
   */
  protected static function getPriority(): int {
    return 200;
  }

  /**
   * Handles exception.
   */
  protected function throwResponse(ExceptionEvent $event): void {
    $response = $this->getResponse($event->getThrowable());
    $event->setResponse($response);
  }

  /**
   * Retrieve JsonResponse.
   */
  protected function getResponse(\Throwable $exception): JsonResponse {
    assert($exception instanceof UsterBaseExceptionInterface);

    $data = [
      'message' => $exception->getMessage(),
    ];

    return new JsonResponse($data, $exception->getStatusCode(), $exception->getHeaders());
  }

  /**
   * Handles errors for this subscriber.
   */
  public function onException(ExceptionEvent $event): void {
    $this->event = $event;
    $exception = $event->getThrowable();
    $request = $event->getRequest();
    $request->attributes->set('exception', $exception);
    $handled_formats = $this->getHandledFormats();
    $format = $request->query->get(
      MainContentViewSubscriber::WRAPPER_FORMAT,
      $request->getRequestFormat()
    );
    if ($this->instanceOf($exception)
      && (empty($handled_formats) || in_array($format, $handled_formats, TRUE))
    ) {
      $this->throwResponse($event);
    }
  }

  /**
   * Verify throwable exception type.
   */
  protected function instanceOf(\Throwable $exception): bool {
    return $exception instanceof UsterBaseExceptionInterface;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[KernelEvents::EXCEPTION][] = ['onException', static::getPriority()];

    return $events;
  }

}
