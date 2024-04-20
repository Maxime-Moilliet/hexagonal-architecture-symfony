<?php

declare(strict_types=1);

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'prepare', namespace: 'symfony', description: 'Symfony server prepare')]
function serverPrepare(): void
{
    io()->title('Proxy domain attach');
    run('symfony proxy:domain:attach mon-ehpad', ['XDEBUG_MODE' => 'off']);
    run('symfony server:ca:install', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'start', namespace: 'symfony', description: 'Symfony server start')]
function serverStart(): void
{
    io()->title('Symfony server start');
    run('symfony server:start --daemon', ['XDEBUG_MODE' => 'off']);
    run('symfony local:proxy:start', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'stop', namespace: 'symfony', description: 'Symfony server stop')]
function serverStop(): void
{
    io()->title('Symfony server stop');
    run('symfony server:stop', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'cache:clear', namespace: 'symfony', description: 'Clear cache', aliases: ['clear'])]
function cacheClear(#[AsOption] ?string $env = 'dev'): void
{
    io()->title('Clear cache');
    run('php bin/console cache:clear', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'cache:warmup', namespace: 'symfony', description: 'Warm up cache', aliases: ['warmup'])]
function cacheWarmup(#[AsOption] ?string $env = 'dev'): void
{
    io()->title('Warm up cache');
    run('php bin/console cache:warmup', ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'debug:router', namespace: 'symfony', description: 'Debug router', aliases: ['router'])]
function router(
    #[AsOption] ?string $env = 'dev',
    #[AsArgument] ?string $name = null
): void {
    io()->title('Debug router');
    $command = ['php bin/console debug:router'];
    if (null !== $name) {
        $command[] = $name;
    }
    run($command, ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'debug:container', namespace: 'symfony', description: 'Debug container', aliases: ['container'])]
function container(
    #[AsOption] ?string $env = 'dev',
    #[AsArgument] ?string $name = null
): void {
    io()->title('Debug container');
    $command = ['php bin/console debug:container'];
    if (null !== $name) {
        $command[] = $name;
    }
    run($command, ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'debug:autowiring', namespace: 'symfony', description: 'Debug autowiring', aliases: ['autowiring'])]
function autowiring(
    #[AsOption] ?string $env = 'dev',
    #[AsArgument] ?string $name = null
): void {
    io()->title('Debug autowiring');
    $command = ['php bin/console debug:autowiring'];
    if (null !== $name) {
        $command[] = $name;
    }
    run($command, ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}

#[AsTask(name: 'debug:dotenv', namespace: 'symfony', description: 'Debug dotenv', aliases: ['dotenv'])]
function dotenv(
    #[AsOption] ?string $env = 'dev',
    #[AsArgument] string $name = 'dev'
): void {
    io()->title('Debug dotenv');
    $command = ['php bin/console debug:dotenv', $name];
    if (null !== $name) {
        $command[] = $name;
    }
    run($command, ['XDEBUG_MODE' => 'off', 'APP_ENV' => $env]);
}
