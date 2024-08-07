<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

final class UnexpectedResponseException extends Exception {

    /**
     * @readonly
     */
    public ResponseInterface $response;

    public function __construct(string $message, ResponseInterface $response) {
        $this->response = $response;
        parent::__construct($message, $response->getStatusCode());
    }

}
