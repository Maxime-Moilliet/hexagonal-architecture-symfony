<?php

declare(strict_types=1);

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'prepare', namespace: 'database', description: 'Create database schema and load fixtures', aliases: ['db'])]
function prepare(#[AsOption] ?string $env = 'dev'): void
{
    dbSchema($env);
    dbFixtures($env);
}

#[AsTask(name: 'schema', namespace: 'database', description: 'Create database schema', aliases: ['schema'])]
function dbSchema(#[AsOption] ?string $env = 'dev'): void
{
    io()->title('Create database schema');
    run('symfony php bin/console doctrine:database:drop --if-exists -f', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
    run('symfony php bin/console doctrine:database:create', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
    run('symfony php bin/console doctrine:migration:migrate --no-interaction --allow-no-migration', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'migration', namespace: 'database', description: 'Create new migration', aliases: ['migration'])]
function dbMigration(): void
{
    io()->title('Create new migration');
    run('symfony php bin/console make:migration', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'fixtures', namespace: 'database', description: 'Load fixtures', aliases: ['fixtures'])]
function dbFixtures(#[AsOption] ?string $env = 'dev'): void
{
    io()->title('Load fixtures');
    run('symfony php bin/console doctrine:fixtures:load -n', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}
