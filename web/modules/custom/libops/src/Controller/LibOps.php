<?php

namespace Drupal\libops\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Site\Settings;

/**
 * LibOps Controller.
 */
class LibOps extends ControllerBase {

    function validBucket(string $bucket) {
        $settings = Settings::get('flysystem', []);
        foreach ($settings as $setting) {
            if (!isset($setting['config']['bucket'])) {
                continue;
            }

            if ($setting['config']['bucket'] == $bucket) {
                return new Response('OK', 200);
            }
        }

        $response = new Response('Not Acceptable', 406);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
