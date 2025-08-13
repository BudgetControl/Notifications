<?php declare(strict_types=1);
namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Facade\Mailer;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetcontrolLibs\Mailer\View\BudgetExceededView;
use BudgetcontrolLibs\Mailer\View\ContactView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BudgetNotifyController extends Controller {

    public function budgetExceeded(Request $request, Response $response, array $args): Response {
        
        $queryParams = $request->getParsedBody();
        $to = $queryParams['to'] ?? null;
        $budgetName = $queryParams['budget_name'] ?? 'Budget';
        $currentAmount = $queryParams['current_amount'] ?? '0';
        $budgetLimit = $queryParams['budget_limit'] ?? '0';
        $currency = $queryParams['currency'] ?? 'EUR';
        $username = $queryParams['username'] ?? 'User';

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }
        $subject = 'Avviso Superamento Budget - ' . $budgetName;

        $view = new BudgetExceededView();
        $view->setUserEmail($to);
        $view->setUserName($username);
        $view->setMessage($budgetName);
        $view->setTotalSpent((float) $currentAmount);
        $view->setBudgetAmount((float) $budgetLimit);
        $view->setSpentPercentage((string)((float) $currentAmount / (float) $budgetLimit * 100));
        $view->setPercentage((string)((float) $currentAmount / (float) $budgetLimit * 100));
        $view->setCurrency($currency);
        $view->setTotalRemaining((float) $budgetLimit - (float) $currentAmount);
        $view->setBudgetAmount((float) $budgetLimit);

        if (!$this->sendMail($to, $subject, $view, 'BudgetExceeded')) {
            return response(['error' => 'Failed to send budget exceeded notification'], 500);
        }

        return response(['message' => 'Budget exceeded notification sent successfully'], 200);
    }
}
