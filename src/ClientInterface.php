<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm;

use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Recipient;

interface ClientInterface {

    /**
     * Sends your notification to the FCM and returns a raw response
     *
     * @see https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages/send
     *
     * @return string the send ID from FCM
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \ErickSkrauch\Fcm\Exception\UnexpectedResponseException
     * @throws \ErickSkrauch\Fcm\Exception\ErrorResponseException
     * @throws \JsonException
     */
    public function send(Message $message, Recipient $recipient): string;

}
