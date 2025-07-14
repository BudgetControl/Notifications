<?php

namespace BudgetControl\Test;

use Slim\Http\Interfaces\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use BudgetControl\Notifications\Http\Controller\AuthNotifyController;

class AuthNotifyControllerTest extends BaseCase
{
    public function test_recovery_password_email()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryParams = [
            'to' => 'user@example.com',
            'token' => 'abc123token',
            'url' => 'https://example.com/reset?token=abc123token',
            'username' => 'User Test'
        ];

        $request->method('getParsedBody')->willReturn($queryParams);
        $controller = new AuthNotifyController();

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $result = $controller->recoveryPassword($request, $response, []);

        $this->assertEquals(200, $result->getStatusCode());

        // Give MailHog time to receive the email
        sleep(1);

        $testData = [
            'to' => 'user@example.com',
            'subject' => 'Password Recovery Request',
            'string_to_check' => ['Ripristina la tua password']
        ];

        $this->invokeMailhog($testData);
    }

    public function test_sign_up_email()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryParams = [
            'to' => 'newuser@example.com',
            'token' => 'confirmation123',
            'url' => 'https://example.com/confirm?token=confirmation123',
            'username' => 'John Doe'
        ];

        $request->method('getParsedBody')->willReturn($queryParams);
        $controller = new AuthNotifyController();

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $result = $controller->signUp($request, $response, []);

        $this->assertEquals(200, $result->getStatusCode());

        // Give MailHog time to receive the email
        sleep(1);

        $testData = [
            'to' => 'newuser@example.com',
            'subject' => 'Welcome! Please confirm your account',
            'string_to_check' => ['Benvenuto in BudgetControl,']
        ];

        $this->invokeMailhog($testData);
    }
}
