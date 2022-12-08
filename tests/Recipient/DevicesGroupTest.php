<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Recipient;

use ErickSkrauch\Fcm\Recipient\DevicesGroup;
use ErickSkrauch\Fcm\Recipient\Recipient;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Recipient\DevicesGroup
 */
final class DevicesGroupTest extends TestCase {

    public function testCorrectUsage(): void {
        $model = new DevicesGroup(['mock 1', 'mock 2']);
        $this->assertSame(Recipient::PARAM_REGISTRATION_IDS, $model->getConditionParam());
        $this->assertSame(['mock 1', 'mock 2'], $model->getConditionValue());
    }

    /**
     * @dataProvider getInvalidCases
     * @param string[] $input
     */
    public function testInvalidUsage(array $input): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The array must contain at least 1 and at most 1000 registration tokens.');

        new DevicesGroup($input);
    }

    /**
     * @return array<string, array<string[]>>
     */
    public function getInvalidCases(): iterable {
        yield 'empty array' => [[]];
        yield 'too much devices' => [array_fill(0, 1001, 'mock token')];
    }

}
