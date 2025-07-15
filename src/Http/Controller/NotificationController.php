<?php declare(strict_types=1);
namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Facade\Mailer;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetcontrolLibs\Mailer\Service\ClientMail;
use BudgetcontrolLibs\Mailer\View\ContactView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class NotificationController extends Controller {

    public function sendEmail(Request $request, Response $response): Response {

        $body = $request->getParsedBody();
        $to = $body['to'];
        $subject = $body['subject'];
        $message = $body['message'];
        $username = $body['user_name'];

        if(!isset($to) || empty($to)) {
            return response(['error' => 'Recipient email is required'], 400);
        }

        $view = new ContactView();
        $view->setMessageBody($message);
        $view->setUserEmail($to);
        $view->setUserName($username);

        try {
        Mailer::send($to, $subject, $view);
        } catch (\Exception $e) {
            return response(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

        return response(['message' => 'Email sent successfully'], 200);
    }
    
}