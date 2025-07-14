<?php

namespace BudgetControl\Test;

use Slim\Http\Interfaces\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use BudgetControl\Notifications\Http\Controller\WorkspaceNotifyController;

class WorkspaceNotifyControllerTest extends BaseCase
{
    public function test_workspace_share_email()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryParams = [
            'to' => 'user@example.com',
            'workspace_name' => 'Team Budget',
            'shared_by' => 'John Doe',
            'role' => 'editor',
            'invitation_url' => 'https://example.com/invite/abc123'
        ];

        $request->method('getParsedBody')->willReturn($queryParams);
        $controller = new WorkspaceNotifyController();

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $result = $controller->workspaceShare($request, $response, []);

        $this->assertEquals(200, $result->getStatusCode());

        // Give MailHog time to receive the email
        sleep(1);

        $testData = [
            'to' => 'user@example.com',
            'subject' => 'You have been invited to join Team Budget',
            'string_to_check' => ['ha condiviso con te il workspace', 'Team Budget', 'John Doe']
        ];

        $this->invokeMailhog($testData);
    }
}
