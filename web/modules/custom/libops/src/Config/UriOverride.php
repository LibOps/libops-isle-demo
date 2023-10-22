<?php

namespace Drupal\libops\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

class UriOverride implements ConfigFactoryOverrideInterface {

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = [];

    // find all image and file fields
    $d_args = [
      ':field' => 'field.storage.%',
      ':image' => '%"type";s:5:"image"%',
      ':file' => '%"type";s:4:"file"%'
    ];
    $fields = \Drupal::database()->query('SELECT name, data FROM config
      WHERE name LIKE :field
      AND (
        data LIKE :image
        OR data LIKE :file
      )', $d_args)->fetchAllKeyed();

    // see if any file/image field point to a GCS bucket
    foreach ($fields as $field => $data) {
      if (in_array($field, $names)) {
        $config = unserialize($data);
        $uri = isset($config['settings']['uri_scheme']) ? $config['settings']['uri_scheme'] : '';
        // if this field points to a GCS bucket, ensure the field storage points to this environment
        if (substr($uri, 0, 3) == 'gs-') {
          list($prefix, $env) = explode('-', $uri);
          if ($env !== getenv('LIBOPS_ENVIRONMENT')) {
            $overrides[$field]['settings']['uri_scheme'] = 'gs-' . getenv('LIBOPS_ENVIRONMENT');
          }
        }
      }
    }


    // find all actions with media creation events
    $d_args = [
      ':action' => 'system.action.%',
      ':uri' => '%s:6:"scheme";s:14:"gs-development"%',
    ];
    $actions = \Drupal::database()->query('SELECT name, data FROM config
      WHERE name LIKE :action
      AND data LIKE :uri', $d_args)->fetchAllKeyed();

    // see if any islandora actions point to a GCS bucket
    foreach ($actions as $action => $data) {
      if (in_array($action, $names)) {
        $config = unserialize($data);
        $uri = isset($config['configuration']['scheme']) ? $config['configuration']['scheme'] : '';
        // if this action used a GCS bucket in its uri schema, ensure the action points to this environment
        if (substr($uri, 0, 3) == 'gs-') {
          list($prefix, $env) = explode('-', $uri);
          if ($env !== getenv('LIBOPS_ENVIRONMENT')) {
            $overrides[$action]['configuration']['scheme'] = 'gs-' . getenv('LIBOPS_ENVIRONMENT');
          }
        }
      }
    }
    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'UriOverride';
  }
  
  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}
