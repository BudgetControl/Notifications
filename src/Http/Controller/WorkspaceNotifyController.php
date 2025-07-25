<?php declare(strict_types=1);
namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Facade\Mailer;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetcontrolLibs\Mailer\Service\ClientMail;
use BudgetcontrolLibs\Mailer\View\ContactView;
use BudgetcontrolLibs\Mailer\View\ShareWorkspaceView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WorkspaceNotifyController extends Controller {

    public function workspaceShare(Request $request, Response $response, array $args): Response {
        
        $queryParams = $request->getParsedBody();
        $to = $queryParams['to'] ?? null;
        $workspaceName = $queryParams['workspace_name'] ?? 'Workspace';
        $sharedBy = $queryParams['shared_by'] ?? 'User';
        $username = $queryParams['username'] ?? 'User Test';

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }

        $subject = 'You have been invited to join ' . $workspaceName;
       
        $view = new ShareWorkspaceView();
        $view->setWorkspaceName($workspaceName);
        $view->setUserFrom($sharedBy);
        $view->setUserEmail($to);
        $view->setUserName($username);

        try {
            Mailer::send($to, $subject, $view);
        } catch (\Exception $e) {
            return response(['error' => 'Failed to send workspace share notification: ' . $e->getMessage()], 500);
        }

        return response(['message' => 'Workspace share notification sent successfully'], 200);
    }
}
