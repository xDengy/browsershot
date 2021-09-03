<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'parser.azbuka-novostroek.com');

// Project repository
set('repository', 'git@github.com:falur/parser.azbuka.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('bin/php', '/usr/bin/php8.0');
set('bin/composer', 'php8.0 /usr/bin/composer');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
host('5.101.180.81')
    ->user('root')
    ->set('deploy_path', '/home/www/parser.azbuka-novostroek.com');

// Tasks
task('npm:install', function () {
    run('cd {{release_path}} && npm install');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy', 'npm:install');

