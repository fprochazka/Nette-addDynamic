<?php

/**
 * My Application bootstrap file.
 */


use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route;


// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';


// Enable Nette\Debug for error visualisation & logging
Debugger::$strictMode = TRUE;
Debugger::enable();

// Load configuration from config.neon file
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);

# Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger();

$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

$configurator->addConfig(__DIR__ . '/config.neon');

$container = $configurator->createContainer();

$router = $container->router;
$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

# Configure and run the application!
$application = $container->application;
$application->run();
