<?php

declare(strict_types=1);

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'update', namespace: 'composer', description: 'Update dependencies')]
function depsUpdate(): void
{
    io()->title('Update dependencies');
    run('composer update', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'install', namespace: 'composer', description: 'Install dependencies')]
function depsInstall(): void
{
    io()->title('Install dependencies');
    run('COMPOSER_MEMORY_LIMIT=-1 composer install -v ', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'require', namespace: 'composer', description: 'Install new dependencies', aliases: ['req'])]
function depsRequire(
    #[AsArgument] string $packages,
    #[AsOption] bool $dev = false,
): void {
    io()->title('Install new dependencies');
    $command = ['composer', 'require', $packages];
    if ($dev) {
        $command[] = '--dev';
    }
    run($command, ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'autoload', namespace: 'composer', description: 'Dump autoload', aliases: ['autoload'])]
function depsAutoload(): void
{
    io()->title('Dump autoload');
    run('composer dump-autoload', ['XDEBUG_MODE' => 'off']);
}
