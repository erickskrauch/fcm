<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Recipient;

use ErickSkrauch\Fcm\Recipient\MultipleTopics;
use ErickSkrauch\Fcm\Recipient\Recipient;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Recipient\MultipleTopics
 */
final class MultipleTopicsTest extends TestCase {

    public function testCorrectUsage(): void {
        $model = new MultipleTopics(['topic1', 'topic2']);
        $this->assertSame(Recipient::PARAM_CONDITION, $model->getConditionParam());
        $this->assertSame("'topic1' in topics || 'topic2' in topics", $model->getConditionValue());
    }

    /**
     * @dataProvider getInvalidCases
     * @param string[] $input
     */
    public function testInvalidUsage(array $input): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You must provide at least 2 topics.');

        new MultipleTopics($input);
    }

    /**
     * @return array<string, array<string[]>>
     */
    public function getInvalidCases(): iterable {
        yield 'empty array' => [[]];
        yield 'single topic' => [['mock topic']];
    }

}
