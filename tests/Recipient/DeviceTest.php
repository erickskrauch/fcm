<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Recipient;

use ErickSkrauch\Fcm\Recipient\Device;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Recipient\Device
 */
final class DeviceTest extends TestCase {

    public function test(): void {
        $model = new Device('mock token');
        $this->assertSame('token', $model->getConditionParam());
        $this->assertSame('mock token', $model->getConditionValue());
    }

}
