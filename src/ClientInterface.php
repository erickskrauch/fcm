<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm;

use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Recipient;
use ErickSkrauch\Fcm\Response\SendResponse;

interface ClientInterface {

    /**
     * Sends your notification to the FCM and returns a raw response
     *
     * @param Message $message
     * @param Recipient $recipient
     *
     * @return SendResponse
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \ErickSkrauch\Fcm\Exception\UnexpectedResponseException
     */
    public function send(Message $message, Recipient $recipient): SendResponse;

}
