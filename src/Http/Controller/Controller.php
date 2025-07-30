<?php
namespace BudgetControl\Notifications\Http\Controller;

use Budgetcontrol\Library\Model\User;
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
}