<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Response;

/**
 * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#table5
 */
final class SendResponse {

    /**
     * @readonly
     */
    public int $multicastId;

    /**
     * @readonly
     */
    public int $countSuccess;

    /**
     * @readonly
     */
    public int $countFailures;

    /**
     * @var array<array{message_id: string}|array{error: string}>
     * @readonly
     */
    public array $results;

    /**
     * @param array<array{message_id: string}|array{error: string}> $results
     */
    public function __construct(int $multicastId, int $success, int $failure, array $results) {
        $this->multicastId = $multicastId;
        $this->countSuccess = $success;
        $this->countFailures = $failure;
        $this->results = $results;
    }

}
