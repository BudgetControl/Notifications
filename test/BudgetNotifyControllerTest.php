<?php

namespace BudgetControl\Test;

use Slim\Http\Interfaces\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use BudgetControl\Notifications\Http\Controller\BudgetNotifyController;

class BudgetNotifyControllerTest extends BaseCase
{
    public function test_budget_exceeded_email()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryParams = [
            'to' => 'user@example.com',
            'budget_name' => 'Monthly Budget',
            'current_amount' => '1200.00',
            'budget_limit' => '1000.00',
            'currency' => 'EUR',
            'username' => 'User Test'
        ];

        $request->method('getParsedBody')->willReturn($queryParams);
        $controller = new BudgetNotifyController();

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $result = $controller->budgetExceeded($request, $response, []);

        $this->assertEquals(200, $result->getStatusCode());

        // Give MailHog time to receive the email
        sleep(1);

        $testData = [
            'to' => 'user@example.com',
            'subject' => 'Avviso Superamento Budget - Monthly Budget',
            'string_to_check' => ['udget impostato','1200', 'User Test']
        ];

        $this->invokeMailhog($testData);
    }

}
