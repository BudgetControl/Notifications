<?php

/**
 * Firebase configuration settings.
 */


if (env('APP_ENV') != 'testing') {
    if (!env('FIREBASE_SERVICE_ACCOUNT')) {
        throw new \Exception('FIREBASE_SERVICE_ACCOUNT environment variable is not set.');
    }

    if (!file_exists(__DIR__ . '/' . env('FIREBASE_SERVICE_ACCOUNT'))) {
        throw new \Exception('Firebase service account file does not exist: ' . env('FIREBASE_SERVICE_ACCOUNT'));
    }

    $firebaseFactory = (new \Kreait\Firebase\Factory)->withServiceAccount(__DIR__ . '/' . env('FIREBASE_SERVICE_ACCOUNT', 'firebase_credentials.json'));
    $firebase = new \BudgetControl\Notifications\Services\FirebaseNotification($firebaseFactory);
} else {
    // dummy firebase service for testing
    $firebase = new \BudgetControl\Notifications\Services\FirebaseNotification(new \Kreait\Firebase\Factory());
}
