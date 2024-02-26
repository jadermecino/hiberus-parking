<?php


# Trusted host patterns.
$settings['trusted_host_patterns'] = explode(',', getenv('DRUPAL_TRUSTED_HOST_PATTERNS'));

# Database
$databases['default']['default'] = array (
  'database' => getenv('DRUPAL_DATABASES_DEFAULT_DATABASE'),
  'username' => getenv('DRUPAL_DATABASES_DEFAULT_USER'),
  'password' => getenv('DRUPAL_DATABASES_DEFAULT_PASSWORD'),
  'prefix' => getenv('DRUPAL_DATABASES_DEFAULT_PREFIX'),
  'host' => getenv('DRUPAL_DATABASES_DEFAULT_HOST'),
  'port' => getenv('DRUPAL_DATABASES_DEFAULT_PORT'),
  'isolation_level' => getenv('DRUPAL_DATABASES_DEFAULT_ISOLATION_LEVEL'),
  'driver' => getenv('DRUPAL_DATABASES_DEFAULT_DRIVER'),
  'namespace' => getenv('DRUPAL_DATABASES_DEFAULT_NAMESPACE'),
  'autoload' => getenv('DRUPAL_DATABASES_DEFAULT_AUTOLOAD'),
);

# Redis config.
$settings['cache']['default'] = getenv('DRUPAL_CACHE_DRIVER');
$settings['redis.connection']['interface'] = getenv('DRUPAL_CACHE_INTERFACE');
$settings['redis.connection']['host'] = getenv('DRUPAL_CACHE_HOST');
$settings['redis.connection']['port'] = getenv('DRUPAL_CACHE_PORT');
$settings['cache_prefix']['default'] = getenv('DRUPAL_CACHE_PREFIX');
$settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';
$settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

$class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');

$settings['bootstrap_container_definition'] = [
  'parameters' => [],
  'services' => [
    'redis.factory' => [
      'class' => 'Drupal\redis\ClientFactory',
    ],
    'cache.backend.redis' => [
      'class' => 'Drupal\redis\Cache\CacheBackendFactory',
      'arguments' => ['@redis.factory', '@cache_tags_provider.container', '@serialization.phpserialize'],
    ],
    'cache.container' => [
      'class' => '\Drupal\redis\Cache\PhpRedis',
      'factory' => ['@cache.backend.redis', 'get'],
      'arguments' => ['container'],
    ],
    'cache_tags_provider.container' => [
      'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
      'arguments' => ['@redis.factory'],
    ],
    'serialization.phpserialize' => [
      'class' => 'Drupal\Component\Serialization\PhpSerialize',
    ],
  ],
];

#$settings['container_yamls'][] = $app_root . '/sites/development.services.yml';
#$settings['container_yamls'][] = $app_root . '/' . $site_path . '/debug.services.yml';
