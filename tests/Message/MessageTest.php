<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Message;

use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Message\Notification;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Message\Message
 */
final class MessageTest extends TestCase {

    public function test(): void {
        $notification = new Notification();
        $model = new Message();
        $model->setNotification($notification);
        $model->setRestrictedPackageName('mock.package');
        $model->setCollapseKey('mock collapse key');
        $model->setPriority(Message::PRIORITY_HIGH);
        $model->setTimeToLive(3600);
        $model->setMutableContent();
        $model->setContentAvailable();
        $model->setImage('https://example.com/image.png');
        $model->setData(['key' => 'value']);

        $this->assertSame(
            [
                'notification' => $notification,
                'restricted_package_name' => 'mock.package',
                'collapse_key' => 'mock collapse key',
                'priority' => Message::PRIORITY_HIGH,
                'time_to_live' => 3600,
                'mutable_content' => true,
                'content_available' => true,
                'image' => 'https://example.com/image.png',
                'data' => '{"key":"value"}',
            ],
            $model->getPayload(),
        );
    }

}
