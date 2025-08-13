<?php
// Autoload Composer dependencies

use \Illuminate\Support\Carbon as Date;
use Illuminate\Support\Facades\Facade;

require_once __DIR__ . '/../vendor/autoload.php';

// Set up your application configuration
// Initialize slim application
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$_ENV['APP_KEY'] = env('APP_KEY', 'testing');

// Crea un'istanza del gestore del database (Capsule)
$capsule = new \Illuminate\Database\Capsule\Manager();

// Aggiungi la configurazione del database al Capsule
$connections = require_once __DIR__.'/../config/database.php';
$connection = env('DB_CONNECTION', 'mysql');
$capsule->addConnection($connections[$connection]);

// Esegui il boot del Capsule
$capsule->bootEloquent();
$capsule->setAsGlobal();

// dipendencies
require_once __DIR__ . '/../config/dependencies.php';

// Set up the logger
require_once __DIR__ . '/../config/logger.php';

// Set up the mailer
require_once __DIR__ . '/../config/mailer.php';

// Set up the Firebase service
require_once __DIR__ . '/../config/firebase.php';

// Set up the Facade application
Facade::setFacadeApplication([
    'log' => $logger,
    'date' => new Date(),
    'mailer' => $mailer,
    'firebase' => $firebase,
]);
