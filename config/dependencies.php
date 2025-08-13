<?php
use DI\Container;

/**
 * Dependence Injection Configuration
 */

$container = new Container();

$dependencies = [
    'BudgetControl\Notifications\Repositories\FcmTokenRepository' => \DI\autowire(),
    'BudgetControl\Notifications\Http\Controller\MessageNotifyController' => \DI\autowire()
        ->constructorParameter('fcmTokenRepository', \DI\get('BudgetControl\Notifications\Repositories\FcmTokenRepository')),
];

foreach ($dependencies as $id => $definition) {
    $container->set($id, $definition);
}

\Slim\Factory\AppFactory::setContainer($container);