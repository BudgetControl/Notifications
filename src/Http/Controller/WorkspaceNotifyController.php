<?php declare(strict_types=1);
namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Facade\Mailer;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetcontrolLibs\Mailer\Service\ClientMail;
use BudgetcontrolLibs\Mailer\View\ContactView;
use BudgetcontrolLibs\Mailer\View\ShareWorkspaceView;
use BudgetcontrolLibs\Mailer\View\UnShareWorkspaceView;
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
        $view->setDashboardUrl(env('APP_URL', 'https://app.budgetcontrol.cloud') . '/dashboard');

        if (!$this->sendMail($to, $subject, $view, 'WorkspaceShare')) {
            return response(['error' => 'Failed to send workspace share notification'], 500);
        }

        return response(['message' => 'Workspace share notification sent successfully'], 200);
    }

    public function workspaceUnShare(Request $request, Response $response, array $args): Response {
        
        $queryParams = $request->getParsedBody();
        $to = $queryParams['to'] ?? null;
        $workspaceName = $queryParams['workspace_name'] ?? 'Workspace';
        $unsharedBy = $queryParams['unshared_by'] ?? 'User';
        $username = $queryParams['username'] ?? 'User Test';

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }

        $subject = 'You have been removed from ' . $workspaceName;

        $view = new UnShareWorkspaceView();
        $view->setWorkspaceName($workspaceName);
        $view->setUserFrom($unsharedBy);
        $view->setUserEmail($to);
        $view->setUserName($username);

        if (!$this->sendMail($to, $subject, $view, 'WorkspaceUnShare')) {
            return response(['error' => 'Failed to send workspace unshare notification'], 500);
        }

        return response(['message' => 'Workspace unshare notification sent successfully'], 200);
    }

}
