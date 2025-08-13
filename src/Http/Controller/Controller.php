<?php
namespace BudgetControl\Notifications\Http\Controller;

use Budgetcontrol\Library\Model\User;
use BudgetControl\Notifications\Facade\Mailer;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Controller {

    public function monitor(Request $request, Response $response)
    {
        return response([
            'success' => true,
            'message' => 'Authentication service is up and running'
        ]);
        
    }

    /**
     * Retrieves the user ID associated with the given UUID.
     *
     * @param string $uuid The UUID of the user.
     * @return int|null The user ID if found, or null if not found.
     */
    protected function getUserIDFromUUID(string $uuid): ?int
    {
        $userId = User::where('uuid', $uuid)->value('id');
        return $userId ? (int)$userId : null;
    }

    /**
     * Invia una mail e gestisce la log dell'eccezione.
     *
     * @param string $to
     * @param string $subject
     * @param object $view
     * @param string $logContext
     * @return bool True se invio ok, false se errore
     */
    protected function sendMail(string $to, string $subject, $view, string $logContext = 'Mailer'): bool
    {
        try {
            Mailer::send($to, $subject, $view);
            return true;
        } catch (\Exception $e) {
            Log::error("[$logContext] Failed to send mail: " . $e->getMessage());
            return false;
        }
    }
}