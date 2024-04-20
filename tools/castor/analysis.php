<?php

declare(strict_types=1);

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'all', namespace: 'analysis', description: 'Run quality analysis', aliases: ['analysis'])]
function analysis(): void
{
    io()->title('Run quality analysis');
    analysisPhpStan();
    analysisPhpCsFixer();
    analysisDoctrine();
    analysisYaml();
    analysisContainer();
}

#[AsTask(name: 'phpstan', namespace: 'analysis', description: 'Run PHPStan', aliases: ['phpstan'])]
function analysisPhpStan(): void
{
    io()->title('Run PHPStan');
    run('php vendor/bin/phpstan analyse -c tools/phpstan.neon', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'php-cs-fixer', namespace: 'analysis', description: 'Run PHP CS Fixer (dry run)', aliases: ['csfixer'])]
function analysisPhpCsFixer(): void
{
    io()->title('Run PHP CS Fixer (dry run)');
    run('php vendor/bin/php-cs-fixer fix --dry-run --config=tools/php-cs-fixer.php', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'doctrine', namespace: 'analysis', description: 'Run Doctrine lint')]
function analysisDoctrine(): void
{
    io()->title('Run Doctrine lint');
    run('php bin/console doctrine:schema:valid --skip-sync', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'yaml', namespace: 'analysis', description: 'Run Yaml lint')]
function analysisYaml(): void
{
    io()->title('Run Yaml lint');
    run('php bin/console lint:yaml config --parse-tags', ['XDEBUG_MODE' => 'off']);
}

#[AsTask(name: 'container', namespace: 'analysis', description: 'Run Container lint')]
function analysisContainer(): void
{
    io()->title('Run Container lint');
    run('php bin/console lint:container', ['XDEBUG_MODE' => 'off']);
}
