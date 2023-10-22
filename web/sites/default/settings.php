<?php

/**
 * Include LibOps settings.php
 * 
 * This includes defaults for the LibOps environment like the database connection and file settings.
 * 
 * Removing this include will cause the site to break. 
 */
require_once "/libops.php";

$settings['config_sync_directory'] = '../config/sync';
