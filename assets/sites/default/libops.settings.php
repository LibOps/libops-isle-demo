<?php

/**
 * Include LibOps settings.php
 * 
 * This includes defaults for the LibOps environment like the database connection and file settings.
 * 
 * Removing this include will cause the site to break. 
 */
require_once "/libops.php";

$settings['hash_salt'] = getenv('LIBOPS_HASH_SALT');
$settings['config_sync_directory'] = '../config/sync';

/**
 * Add ISLE specific settings
 */
$config['islandora.settings']['broker_url'] = 'tcp://activemq:61613';
$config['islandora_iiif.settings']['iiif_server'] = getenv('LIBOPS_IIIF_URL');
$config['openseadragon.settings']['iiif_server'] = getenv('LIBOPS_IIIF_URL');
$config['matomo.settings']['url_http'] = getenv('CLOUD_RUN_URL') . '/matomo/';
$config['matomo.settings']['url_https'] = getenv('CLOUD_RUN_URL') . '/matomo/';

$settings['flysystem']['fedora'] = [
  'driver' => 'fedora',
  'config' => [
    'root' => 'http://fcrepo:8080/fcrepo/rest',
  ],
];

// remove this code block to disable redis cache store
if (!Drupal\Core\Installer\InstallerKernel::installationAttempted() &&
  extension_loaded('redis') &&
  file_exists('/code/web/modules/contrib/redis/redis.services.yml')) {
    $settings['redis.connection']['host'] = 'redis';
    $settings['redis.connection']['port'] = 6379;
    $settings['redis.connection']['interface'] = 'PhpRedis';
    $settings['cache']['default'] = 'cache.backend.redis';
    $settings['redis_compress_length'] = 100;
    $settings['queue_default'] = 'queue.redis';
    $settings['redis.connection']['persistent'] = TRUE;
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
}
// end redis config
