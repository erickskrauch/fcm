<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

use InvalidArgumentException;

final class MultipleTopics implements Recipient {

    /**
     * @var string[]
     */
    private array $topics;

    /**
     * @param string[] $topics
     */
    public function __construct(array $topics) {
        if (count($topics) < 2) {
            throw new InvalidArgumentException('You must provide at least 2 topics.');
        }

        $this->topics = $topics;
    }

    public function getConditionParam(): string {
        return Recipient::PARAM_CONDITION;
    }

    public function getConditionValue(): string {
        return implode(' || ', array_map(fn($topic) => "'{$topic}' in topics", $this->topics));
    }

}
