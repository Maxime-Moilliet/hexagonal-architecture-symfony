<?php

declare(strict_types=1);

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'all', namespace: 'tests', description: 'Run all tests', aliases: ['tests'])]
function tests(): void
{
    io()->title('Run all tests');
    unitTests();
    componentTests();
    integrationTests();
}

#[AsTask(name: 'coverage', description: 'Run all tests with coverage', aliases: ['coverage'])]
function testsWithCoverage(): void
{
    io()->title('Run all tests with coverage');
    run('php vendor/bin/simple-phpunit');
}

#[AsTask(name: 'unit', description: 'Run unit tests', aliases: ['unit'])]
function unitTests(): void
{
    io()->title('Run unit tests');
    run('php vendor/bin/simple-phpunit --testdox --testsuite=unit --no-coverage', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'component', description: 'Run component tests', aliases: ['component'])]
function componentTests(): void
{
    io()->title('Run component tests');
    run('php vendor/bin/simple-phpunit --testdox --testsuite=component --no-coverage', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'integration', description: 'Run integration tests', aliases: ['integration'])]
function integrationTests(): void
{
    io()->title('Run integration tests');
    run('php vendor/bin/simple-phpunit --testdox --testsuite=integration --no-coverage', ['XDEBUG_MODE' => 'off']);
}
