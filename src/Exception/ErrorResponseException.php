<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

final class ErrorResponseException extends Exception {

    /**
     * @readonly
     */
    public string $errorCode;

    /**
     * @readonly
     */
    public ResponseInterface $response;

    public function __construct(string $message, string $errorCode, ResponseInterface $response) {
        $this->errorCode = $errorCode;
        $this->response = $response;
        parent::__construct($message, $response->getStatusCode());
    }

}
