<?php

namespace BudgetControl\Test;

use Slim\Http\Interfaces\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use BudgetControl\Notifications\Repositories\FcmTokenRepository;
use BudgetControl\Notifications\Http\Controller\BudgetNotifyController;
use BudgetControl\Notifications\Http\Controller\MessageNotifyController;

class MessageNotificationsTest extends BaseCase
{
    public function test_send_notification_to_user()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryParams = [
            'title' => 'Budget Exceeded',
            'body' => 'Your budget for this month has been exceeded.',
        ];

        $arg = [
            'userUuid' => 'unique-uuid-1234',
        ];

        $request->method('getParsedBody')->willReturn($queryParams);
        $controller = new MessageNotifyController(new FcmTokenRepository());

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $result = $controller->sendNotificationToUser($request, $response, $arg);

        $this->assertEquals(200, $result->getStatusCode());

    }

}
