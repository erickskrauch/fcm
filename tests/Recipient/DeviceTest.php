<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Recipient;

use ErickSkrauch\Fcm\Recipient\Device;
use ErickSkrauch\Fcm\Recipient\Recipient;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Recipient\Device
 */
final class DeviceTest extends TestCase {

    public function test(): void {
        $model = new Device('mock token');
        $this->assertSame(Recipient::PARAM_TO, $model->getConditionParam());
        $this->assertSame('mock token', $model->getConditionValue());
    }

}
