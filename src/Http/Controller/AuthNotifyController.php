<?php declare(strict_types=1);
namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Facade\Mailer;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetcontrolLibs\Mailer\View\RecoveryPasswordView;
use BudgetcontrolLibs\Mailer\View\SignUpView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthNotifyController extends Controller {

    public function recoveryPassword(Request $request, Response $response, array $args): Response {
        
        $queryParams = $request->getParsedBody();
        $to = $queryParams['to'] ?? null;
        $resetUrl = $queryParams['url'] ?? null;
        $username = $queryParams['username'];

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }

        $subject = 'Password Recovery Request';

        $view = new RecoveryPasswordView();
        $view->setLink($resetUrl);
        $view->setUserName($username);
        $view->setUserEmail($to);

        try {
            Mailer::send($to, $subject, $view);
        } catch (\Exception $e) {
            return response(['error' => 'Failed to send recovery email: ' . $e->getMessage()], 500);
        }

        return response(['message' => 'Password recovery email sent successfully'], 200);
    }

    public function signUp(Request $request, Response $response, array $args): Response {
        
        $queryParams = $request->getParsedBody();
        $to = $queryParams['to'] ?? null;
        $confirmationUrl = $queryParams['url'] ?? null;
        $username = $queryParams['username'];

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }

        $subject = 'Welcome! Please confirm your account';

        $view = new SignUpView();
        $view->setUserName($username);
        $view->setUserEmail($to);
        $view->setConfirmLink($confirmationUrl);

        try {
            Mailer::send($to, $subject, $view);
        } catch (\Exception $e) {
            return response(['error' => 'Failed to send sign-up confirmation email: ' . $e->getMessage()], 500);
        }

        return response(['message' => 'Sign-up confirmation email sent successfully'], 200);
    }
}
