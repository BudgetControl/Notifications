<?php

use Budgetcontrol\Library\Model\FcmToken;
use Budgetcontrol\Seeds\Resources\Seed;
use Phinx\Seed\AbstractSeed;

class FcmTokenSeed extends AbstractSeed
{

    public function run(): void
    {
        $fcmTokens = [
            [
                'user_id' => 1,
                'token' => uniqid('fcm_token_'),
                'device_info' => 'Device 1 Info',
                'platform' => 'android',
                'lang' => 'en'
            ]
        ];

        foreach ($fcmTokens as $tokenData) {
            $fcmToken = new FcmToken();
            $fcmToken->fill($tokenData);
            $fcmToken->save();
        }

    }
}
