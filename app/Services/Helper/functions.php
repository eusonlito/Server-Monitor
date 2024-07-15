<?php declare(strict_types=1);

/**
 * @return \App\Services\Helper\Helper
 */
function helper(): App\Services\Helper\Helper
{
    static $helper;

    return $helper ??= new App\Services\Helper\Helper();
}

/**
 * @return \App\Services\Helper\Service
 */
function service(): App\Services\Helper\Service
{
    static $service;

    return $service ??= new App\Services\Helper\Service();
}

/**
 * @param string $name
 *
 * @return void
 */
function chronoStart(string $name): void
{
    App\Services\Chrono\Chrono::start($name);
}

/**
 * @param string $name
 *
 * @return void
 */
function chronoStop(string $name): void
{
    App\Services\Chrono\Chrono::stop($name);
}

/**
 * @return array
 */
function chronoSummary(): array
{
    return App\Services\Chrono\Chrono::summary();
}
