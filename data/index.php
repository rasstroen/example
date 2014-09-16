<?php
/**
 * путь до корневой директории
 */
$projectRoot = '/home/sites/balancer1.lj-top.ru/';

/**
 * Управляем кешом уровня nginx сразу - в случае любой ошибки, кеширование отключено.
 * Заголовок кеширования со временем отправляется при выводе в браузер, иначе - 0
 */
header('X-Accel-Expires: 0');

/**
 * Уровень ошибок
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Загружаем настройки проекта
 */
$configuration  = require_once $projectRoot . 'configs' . DIRECTORY_SEPARATOR . 'main.php';

/**
 * Autoloader
 */

require_once $projectRoot . 'lib' . DIRECTORY_SEPARATOR . 'AutoLoader.php';
AutoLoader::init($projectRoot, $configuration['includePathes']);

/**
 * Инициализируем приложение по загруженному конфигу
 */
$application = new \Application\Web($configuration);

/**
 * Запускаем приложение
 */
$application->run();
