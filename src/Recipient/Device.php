<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

final class Device implements Recipient {

    private string $token;

    public function __construct(string $token) {
        $this->token = $token;
    }

    public function getConditionParam(): string {
        return Recipient::PARAM_TO;
    }

    public function getConditionValue(): string {
        return $this->token;
    }

}
