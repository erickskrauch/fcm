<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm;

use ErickSkrauch\Fcm\Recipient\Recipient;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface {

    /**
     * Sends your notification to the FCM and returns a raw response
     *
     * @param Message $message
     * @param Recipient $recipient
     *
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function send(Message $message, Recipient $recipient): ResponseInterface;

}
