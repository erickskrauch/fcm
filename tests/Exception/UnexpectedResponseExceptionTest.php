<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Exception;

use ErickSkrauch\Fcm\Exception\UnexpectedResponseException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \ErickSkrauch\Fcm\Exception\UnexpectedResponseException
 */
final class UnexpectedResponseExceptionTest extends TestCase {

    public function test(): void {
        $response = $this->createMock(ResponseInterface::class);
        $exception = new UnexpectedResponseException($response);
        $this->assertSame('Received an unexpected response from FCM', $exception->getMessage());
        $this->assertSame($response, $exception->getResponse());
    }

}
