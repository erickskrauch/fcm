<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

final class UnexpectedResponseException extends Exception {

    private ResponseInterface $response;

    public function __construct(ResponseInterface $response) {
        parent::__construct('Received an unexpected response from FCM');
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface {
        return $this->response;
    }

}
