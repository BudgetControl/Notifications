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

        if (!$this->sendMail($to, $subject, $view, 'SendEmail')) {
            return response(['error' => 'Failed to send email'], 500);
        }

        return response(['message' => 'Email sent successfully'], 200);
    }
    
}