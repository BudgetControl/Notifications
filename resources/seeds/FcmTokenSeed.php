<?php

use Budgetcontrol\Library\Model\FcmToken;
use Budgetcontrol\Library\Model\User;
use Budgetcontrol\Seeds\Resources\Seed;
use Phinx\Seed\AbstractSeed;

class FcmTokenSeed extends AbstractSeed
{

    public function run(): void
    {

        \Budgetcontrol\Library\Model\User::create([
            'name' => 'Mario',
            'email' => 'mario.rossi2@email.it',
            'password' => 'password',
            'uuid' => 'unique-uuid-1234',
        ]);

        $fcmTokens = [
            [
                'user_id' => 1,
                'token' => uniqid('fcm_token_'),
                'device_info' => 'Device 1 Info',
                'platform' => 'android',
                'lang' => 'en'
            ],
            [
                'user_id' => 2,
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
