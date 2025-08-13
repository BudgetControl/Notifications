<?php declare(strict_types=1);

namespace BudgetControl\Notifications\Repositories;

use Budgetcontrol\Library\Model\FcmToken;
use BudgetControl\Notifications\Traits\BuildQuery;
use BudgetControl\Notifications\Entities\FcmOptions;
use BudgetControl\Notifications\Entities\OptionInterface;

class FcmTokenRepository {

    use BuildQuery;

    protected $tokens = [];

    /**
     * Saves the FCM token for a specific user along with the user agent information.
     *
     * @param int $userId The ID of the user to associate with the token.
     * @param string $token The FCM token to be saved.
     * @param string $userAgent The user agent string of the device.
     * @param string $lang The language preference of the user.
     *
     * @return void
     */
    public function saveToken(int $userId, string $token, string $userAgent, string $lang): void {

        //check if exist 
        $platform = platform($userAgent);
        $existingToken = FcmToken::where('user_id', $userId)->where('token', $token)->first();

        if ($existingToken) {
            return;
        }

        FcmToken::create([
            'user_id' => $userId,
            'token' => $token,
            'device_info' => $userAgent,
            'platform' => $platform,
            'lang' => $lang
        ]);

    }

    /**
     * Retrieves the FCM tokens for users based on the provided options.
     *
     * @param OptionInterface $options The options used to filter and retrieve user tokens.
     * @return array An array of FCM tokens for the selected users.
     */
    public function getUsersToken(OptionInterface $options): array {

        $conditions = $this->buildWhereCondition($options);

        $tokens = FcmToken::where($conditions)
            ->pluck('token')
            ->toArray();

        return array_unique($tokens);

    }

    /**
     * Retrieves the FCM (Firebase Cloud Messaging) token associated with the specified user ID.
     *
     * @param int $userId The unique identifier of the user whose FCM token is to be retrieved.
     * @return string|null The FCM token if found, or null if no token exists for the user.
     */
    public function getTokensByUserId(int $userId): ?array {
        return FcmToken::where('user_id', $userId)->pluck('token')->toArray();
    }
}