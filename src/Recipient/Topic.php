<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

final class Topic implements Recipient {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getConditionParam(): string {
        return Recipient::PARAM_TO;
    }

    public function getConditionValue(): string {
        return "/topics/{$this->name}";
    }

}
