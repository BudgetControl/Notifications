<?php

declare(strict_types=1);

namespace BudgetControl\Notifications\Services;

use Budgetcontrol\Library\Model\FcmToken;
use Illuminate\Support\Facades\Log;
use \Kreait\Firebase\Messaging\CloudMessage;
use \Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MulticastSendReport;

class FirebaseNotification
{

    private \Kreait\Firebase\Factory $firebaseFactory;

    public function __construct(\Kreait\Firebase\Factory $firebaseFactory)
    {
        $this->firebaseFactory = $firebaseFactory;
    }

    /**
     * Sends a notification to the specified device tokens using Firebase.
     *
     * @param array $tokens Array of device tokens to which the notification will be sent.
     * @param string $title The title of the notification.
     * @param string $body The body content of the notification.
     *
     * @return void
     */
    public function sendNotification(array $tokens, string $title, string $body): void
    {
        $messaging = $this->firebaseFactory->createMessaging();
        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()->withNotification($notification);

        try {
            $report = $messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            Log::critical('Failed to send notification: ' . $e->getMessage());
            throw new NotificationServiceException('Failed to send notification: ' . $e->getMessage(), 500);
        }

        $this->findFailedUserTokens($report);

    }

    /**
     * Identifies and processes user tokens that failed during a multicast send operation.
     *
     * This method analyzes the provided MulticastSendReport to find user tokens
     * that did not successfully receive the notification. It can be used to handle
     * failed deliveries, such as logging, retrying, or cleaning up invalid tokens.
     *
     * @param MulticastSendReport $report The report containing the results of the multicast send operation.
     *
     * @return void
     */
    protected function findFailedUserTokens(MulticastSendReport $report): void
    {
        foreach ($report->failures()->getItems() as $failure) {
            $invalidToken = $failure->target()->value();
            FcmToken::where('token', $invalidToken)->delete();
        }
    }
}
