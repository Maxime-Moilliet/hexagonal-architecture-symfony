<?php

declare(strict_types=1);

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\import;
use function Castor\io;

import(__DIR__.'/castor/analysis.php');
import(__DIR__.'/castor/composer.php');
import(__DIR__.'/castor/database.php');
import(__DIR__.'/castor/docker.php');
import(__DIR__.'/castor/fix.php');
import(__DIR__.'/castor/symfony.php');
import(__DIR__.'/castor/tests.php');

#[AsTask(name: 'build', namespace: 'tools', description: 'Build the project', aliases: ['build'])]
function build(#[AsOption] ?string $env = null): void
{
    io()->title('Build the project');
    dockerBuild();
    depsInstall();
    if (null === $env) {
        prepare();
        prepare('test');
    } else {
        prepare($env);
    }
}

#[AsTask(name: 'install', namespace: 'tools', description: 'Install the project', aliases: ['install'])]
function install(#[AsOption] ?string $env = null): void
{
    io()->title('Install the project');
    build($env);
    serverPrepare();
    start();
}

#[AsTask(name: 'start', namespace: 'tools', description: 'Start the project', aliases: ['start'])]
function start(): void
{
    io()->title('Start the project');
    dockerStop();
    serverStop();
    dockerStart();
    serverStart();
}

#[AsTask(name: 'stop', namespace: 'tools', description: 'Stop the project', aliases: ['stop'])]
function stop(): void
{
    io()->title('Stop the project');
    dockerStop();
    serverStop();
}

#[AsTask(name: 'reset', namespace: 'tools', description: 'Reset the project', aliases: ['reset'])]
function resetProject(): void
{
    io()->title('Reset the project');
    dockerDown();
    serverStop();
    install();
}

#[AsTask(name: 'quality', namespace: 'tools', description: 'Run quality analysis', aliases: ['qa'])]
function quality(): void
{
    io()->title('Run quality analysis');
    tests();
    analysis();
}
