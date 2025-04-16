<?php
namespace Deployer;

require 'recipe/flow_framework.php';

// Configuration
ini_set('display_errors', 1);

// Git
set('repository', 'ssh://git@git.sitegeist.de:40022/hoerbiger/hoerbiger-websites.git');

// SSH
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('writable_mode', 'chmod');
set('writable_chmod_mode', '0775');
set('writable_dirs', ['.']);
set('writable_chmod_recursive', false);
set('default_timeout', 1800);
set( 'keep_releases', -1 );

// composer
set('bin/composer', function () {
    return commandExist('composer2')
        ? 'composer2'
        : 'composer';
});

set('composer_options', function() {
    return '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader';
});

/**
 * Servers
 */
inventory('deploy-hosts.yml');

/**
 * Build frontend
 */
task('deploy:build', function () {
    run('cd {{release_path}} && yarn install && yarn build', ['timeout' => 60 * 10]);
})->desc('Install and build');

/**
 * Upload to server
 */
task('upload', function () {
    upload(
        __DIR__ . '/.build/current/',
        '{{release_path}}',
        [
            'options' => [
                '--exclude /node_modules/',
                '--exclude /.git/',
                '--exclude /.ddev/',
                '--exclude /composer.json',
                '--exclude /composer.lock',
                '--exclude /deploy-hosts.yml',
                '--exclude /deploy.php',
                '--exclude /Makefile',
                '--exclude /Readme.md'
            ],
            'flags' => 'az'
        ]
    );
});
/**
 * Run node repair
 */
task('deploy:node_repair', function () {
    run(
        'FLOW_CONTEXT={{flow_context}} {{bin/php}} -d memory_limit=-1 {{release_path}}/{{flow_command}} node:repair --only addMissingDefaultValues,createMissingChildNodes,reorderChildNodes',
        ['timeout' => 60 * 15]
    );
})->desc('Run node repair');

/**
 * Flow cache flush
 */
task('deploy:flush_caches', function () {
    run('FLOW_CONTEXT={{flow_context}} {{bin/php}} {{release_path}}/{{flow_command}} flow:cache:flushone Sitegeist_KlarSchiff_CacheBusterIdentifierCache');
    run('FLOW_CONTEXT={{flow_context}} {{bin/php}} {{release_path}}/{{flow_command}} flow:cache:flush --force');
    run('{{bin/php}} {{release_path}}/../../shared/Scripts/cachetool.phar opcache:reset --fcgi=/var/run/php-fpm/php-fpm.socket');
})->desc('Flush caches');

/**
 * Publish resources
 */
task('deploy:publish_resources', function () {
    run('FLOW_CONTEXT={{flow_context}} {{bin/php}} -d memory_limit=-1 {{release_path}}/{{flow_command}} resource:publish');
})->desc('Publish resources');

/**
 * Deploy and build locally
 */
task('build', function () {
    set('deploy_path', __DIR__ . '/.build');
    invoke('deploy:prepare');
    invoke('deploy:release');
    invoke('deploy:update_code');
    invoke('deploy:vendors');
    invoke('deploy:build');
    invoke('deploy:symlink');
    invoke('cleanup');
    invoke('success');
})->local();

/**
 * Release
 */
task('release', [
    'deploy:prepare',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:flush_caches',
    'deploy:run_migrations',
    'deploy:node_repair',
    'deploy:publish_resources',
    'deploy:writable',
    'deploy:symlink'
]);

/**
 * Main Task
 */
task('deploy', [
    'build',
    'release',
    'cleanup',
    'success'
]);
