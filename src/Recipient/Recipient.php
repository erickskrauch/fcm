<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

interface Recipient {

    public function getConditionParam(): string;

    public function getConditionValue(): string;

}
