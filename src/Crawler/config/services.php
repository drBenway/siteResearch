<?php

/**
 * Dependency Injection
 * @todo not yet implemented
 */

$loader = __DIR__.'/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

$serviceContainer = new ContainerBuilder();
/**
 * Classes
 */
$CrawlerDefinition = new Definition('Classes\Crawler');
$serviceContainer->setDefinition('Crawler', $CrawlerDefinition);
$CrawlerSettingsDefinition = new Definition('Classes\Crawler');
$serviceContainer->setDefinition('CrawlerSettings', $CrawlerSettingsDefinition);

/**
 * DB namespace
 */
$CrawlerDBDefinition = new Definition('DB\CrawlerDB');
$serviceContainer->setDefinition('DB.CrawlerDB', $CrawlerDBDefinition);

/**
 * Tweaks namespace
 */
$TweaksDefinition = new Definition('Tweaks\TweakRunner');
$serviceContainer->setDefinition('TweakRunner', $TweaksDefinition);

/**
 * Filters namespace
 */
$FiltersDefinition = new Definition('Tilters\TweakRunner');
$serviceContainer->setDefinition('FilterRunner', $FiltersDefinition);
