<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Recipient;

use ErickSkrauch\Fcm\Recipient\Topic;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Recipient\Topic
 */
final class TopicTest extends TestCase {

    public function test(): void {
        $model = new Topic('mock topic');
        $this->assertSame('topic', $model->getConditionParam());
        $this->assertSame('mock topic', $model->getConditionValue());
    }

}
