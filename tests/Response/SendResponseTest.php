<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Response;

use ErickSkrauch\Fcm\Response\SendResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Response\SendResponse
 */
final class SendResponseTest extends TestCase {

    public function test(): void {
        $model = new SendResponse(123, 321, 231, [['message_id' => 'mock_1'], ['error' => 'error_1']]);
        $this->assertSame(123, $model->multicastId);
        $this->assertSame(321, $model->countSuccess);
        $this->assertSame(231, $model->countFailures);
        $this->assertSame([['message_id' => 'mock_1'], ['error' => 'error_1']], $model->results);
    }

}
