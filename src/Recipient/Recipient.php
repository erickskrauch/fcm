<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Recipient;

interface Recipient {

    /**
     * This parameter specifies the recipient of a message.
     * The value can be a device's registration token, a device group's notification key,
     * or a single topic (prefixed with /topics/).
     */
    public const PARAM_TO = 'to';

    /**
     * This parameter specifies the recipient of a multicast message,
     * a message sent to more than one registration token.
     */
    public const PARAM_REGISTRATION_IDS = 'registration_ids';

    /**
     * This parameter specifies a logical expression of conditions that determine the message target.
     */
    public const PARAM_CONDITION = 'condition';

    /**
     * @return self::PARAM_TO|self::PARAM_REGISTRATION_IDS|self::PARAM_CONDITION
     */
    public function getConditionParam(): string;

    /**
     * @return string|string[]
     */
    public function getConditionValue();

}
