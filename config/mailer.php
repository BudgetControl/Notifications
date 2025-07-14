<?php

use MLAB\SdkMailer\Smtp\Aruba;
use MLAB\SdkMailer\Smtp\Mailhog;
use BudgetcontrolLibs\Mailer\Service\ClientMail;

if(empty(env('MAIL_FROM_ADDRESS'))) {
    throw new InvalidArgumentException('MAIL_FROM_ADDRESS is not defined in the environment variables.');
};

$mailerConfig = [
    'host' => env('MAIL_HOST', 'localhost'),
    'port' => env('MAIL_PORT', 1025),
    'username' => env('MAIL_USERNAME', ''),
    'password' => env('MAIL_PASSWORD', ''),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
];

switch (env('MAIL_DRIVER')) {
    case 'aruba':
        $smtp = new Aruba($mailerConfig['host'], $mailerConfig['port'], $mailerConfig['username'], $mailerConfig['password'], $mailerConfig['encryption']);
        break;
    case 'mailhog':
        $smtp = new Mailhog($mailerConfig['host'], $mailerConfig['port']);
        break;
    default:
        throw new InvalidArgumentException('Unsupported MAIL_DRIVER: ' . env('MAIL_DRIVER'));
}

$mailer = new ClientMail($smtp, env('MAIL_FROM_ADDRESS'));