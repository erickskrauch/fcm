<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

use InvalidArgumentException;

final class MultipleDevices implements Recipient {

    /**
     * @var string[]
     */
    private array $tokens;

    /**
     * @param string[] $tokens
     */
    public function __construct(array $tokens) {
        if (empty($tokens) || count($tokens) > 1_000) {
            throw new InvalidArgumentException('The array must contain at least 1 and at most 1000 registration tokens.');
        }

        $this->tokens = $tokens;
    }

    public function getConditionParam(): string {
        return Recipient::PARAM_REGISTRATION_IDS;
    }

    /**
     * @return string[]
     */
    public function getConditionValue(): array {
        return $this->tokens;
    }

}
