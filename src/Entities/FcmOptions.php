<?php declare(strict_types=1);

namespace BudgetControl\Notifications\Entities;


final class FcmOptions implements OptionInterface {

    public readonly ?string $platform;
    public readonly ?string $lang;
    public readonly ?string $userId;
    public readonly ?string $token;

    public function __construct(...$options) {
        $this->platform = $options['platform'] ?? null;
        $this->lang = $options['lang'] ?? null;
        $this->userId = $options['userId'] ?? null;
        $this->token = $options['token'] ?? null;
    }
}