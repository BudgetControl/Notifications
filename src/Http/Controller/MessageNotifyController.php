<?php

declare(strict_types=1);

namespace BudgetControl\Notifications\Http\Controller;

use BudgetControl\Notifications\Entities\FcmOptions;
use BudgetControl\Notifications\Http\Controller\Controller;
use BudgetControl\Notifications\Repositories\FcmTokenRepository;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BudgetControl\Notifications\Facade\Firebase;
use BudgetControl\Notifications\Services\NotificationServiceException;

class MessageNotifyController extends Controller
{

    protected FcmTokenRepository $fcmTokenRepository;

    const LANGUAGES = [
        'en',
        'it',
        'fr',
        'es',
        'de',
        'pt',
        'ru',
        'zh',
        'ja',
        'ko'
    ];

    public function __construct(FcmTokenRepository $fcmTokenRepository)
    {
        $this->fcmTokenRepository = $fcmTokenRepository;
    }

    /**
     * Sends a notification message based on the provided request data.
     *
     * @param Request $request  The HTTP request containing notification details.
     * @param Response $response The HTTP response object.
     * @return Response Returns the HTTP response after processing the notification.
     */
    public function sendNotificationToUser(Request $request, Response $response, $arg): Response
    {

        $data = $request->getParsedBody();
        $title = $data['title'] ?? 'Notification';
        $body = $data['body'] ?? null;
        $userUuid = $arg['userUuid'] ?? null;

        if (!isset($body) || empty($body)) {
            return response(['error' => 'Notification body is required'], 400);
        }

        $user = $this->getUserIDFromUUID($userUuid);
        if (is_null($user)) {
            Log::error('User not found for UUID: ' . $userUuid);
            return response(['error' => 'User cannot have access'], 401);
        }

        $tokens = $this->fcmTokenRepository->getTokensByUserId($user);
        if (empty($tokens)) {
            Log::info('No tokens found for user');
            return response(['error' => 'No tokens found for user'], 204);
        }

        try {
            Firebase::sendNotification($tokens, $title, $body);
        } catch (NotificationServiceException $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return response(['error' => 'Failed to send notification: ' . $e->getMessage()], 500);
        }

        return response([
            'success' => true,
            'message' => 'Sent notification successfully',
        ]);
    }

    /**
     * Sends a notification message based on the provided request data.
     *
     * @param Request $request  The HTTP request containing notification details.
     * @param Response $response The HTTP response object.
     * @return Response Returns the HTTP response after processing the notification.
     */
    public function sendNotification(Request $request, Response $response, $arg): Response
    {

        $data = $request->getParsedBody();
        $title = $data['title'] ?? 'Notification';
        $body = $data['body'] ?? null;

        $options = new FcmOptions($data);
        if (!isset($body) || empty($body)) {
            return response(['error' => 'Notification body is required'], 400);
        }

        $userTokens = $this->fcmTokenRepository->getUsersToken($options);

        if (empty($userTokens)) {
            Log::info('No tokens found for user');
            return response(['error' => 'No tokens found for user'], 204);
        }

        try {
            Firebase::sendNotification($userTokens, $title, $body);
        } catch (NotificationServiceException $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            throw $e;
        }

        return response([
            'success' => true,
            'message' => 'Sent notification successfully to all users',
        ]);
    }
    /**
     * Saves the FCM token for a user.
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Exception
     */
    public function saveToken(Request $request, Response $response): Response
    {

        $queryParams = $request->getParsedBody();
        $token = $queryParams['token'] ?? null;
        $userUuid = $queryParams['user_uuid'] ?? null;
        $userAgent = $queryParams['user_agent'] ?? null;

        if (!isset($token) || empty($token)) {
            return response(['error' => 'Token is required'], 400);
        }

        if (!isset($userUuid) || empty($userUuid)) {
            return response(['error' => 'User UUID is required'], 400);
        }

        if (!isset($userAgent) || empty($userAgent)) {
            return response(['error' => 'User Agent is required'], 400);
        }

        $lang = $queryParams['lang'] ?? null;
        if (!isset($lang) || !in_array($lang, self::LANGUAGES)) {
            $lang = 'en'; // Default language if not provided or invalid
        }

        //find user ID form user UUID
        $userId = $this->getUserIDFromUUID($userUuid);
        if (is_null($userId)) {
            Log::error('User not found for UUID: ' . $userUuid);
            return response(['error' => 'User cannot have access'], 401);
        }

        try {
            $this->fcmTokenRepository->saveToken($userId, $token, $userAgent, $lang);
        } catch (\Exception $e) {
            Log::error('Failed to save token: ' . $e->getMessage());
            return response(['error' => 'Failed to save token: ' . $e->getMessage()], 500);
        }

        return response([
            'success' => true,
            'message' => 'Token saved successfully',
        ]);
    }
}
