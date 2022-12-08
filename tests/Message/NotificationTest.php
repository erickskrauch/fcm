<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests\Message;

use ErickSkrauch\Fcm\Message\Notification;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ErickSkrauch\Fcm\Message\Notification
 */
final class NotificationTest extends TestCase {

    private Notification $model;

    protected function setUp(): void {
        parent::setUp();
        $this->model = new Notification();
    }

    public function testSetters(): void {
        $this->model->setTitle('mock title');
        $this->model->setSubtitle('mock subtitle');
        $this->model->setBody('mock body');
        $this->model->setBadge(123);
        $this->model->setIcon('mock icon');
        $this->model->setColor('#ffffff');
        $this->model->setClickAction('MOCK_ACTION');
        $this->model->setTag('mock tag');
        $this->model->setSound('mock_sound.mp3');
        $this->model->setAndroidChannelId('mock android channel id');
        $this->assertSame(
            [
                'title' => 'mock title',
                'subtitle' => 'mock subtitle',
                'body' => 'mock body',
                'badge' => 123,
                'icon' => 'mock icon',
                'color' => '#ffffff',
                'click_action' => 'MOCK_ACTION',
                'tag' => 'mock tag',
                'sound' => 'mock_sound.mp3',
                'android_channel_id' => 'mock android channel id',
            ],
            $this->model->jsonSerialize(),
        );
    }

    public function testLocalizedTitleAndBodyWithArgs(): void {
        $this->model->setTitleLocKey('title-loc-key', ['title-loc-arg1', 'title-loc-arg2']);
        $this->model->setBodyLocKey('body-loc-key', ['body-loc-arg1', 'body-loc-arg2']);
        $this->assertSame(
            [
                'title_loc_key' => 'title-loc-key',
                'title_loc_args' => '["title-loc-arg1","title-loc-arg2"]',
                'body_loc_key' => 'body-loc-key',
                'body_loc_args' => '["body-loc-arg1","body-loc-arg2"]',
            ],
            $this->model->jsonSerialize(),
        );
    }

    public function testLocalizedTitleAndBodyWithoutArgs(): void {
        $this->model->setTitleLocKey('title-loc-key');
        $this->model->setBodyLocKey('body-loc-key');
        $this->assertSame(
            [
                'title_loc_key' => 'title-loc-key',
                'body_loc_key' => 'body-loc-key',
            ],
            $this->model->jsonSerialize(),
        );
    }

    public function testTitleInvariant(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You cannot use "title" and "title_loc_key" at the same time');

        $this->model->setTitleLocKey('title-loc-key');
        $this->model->setTitle('mock title');
    }

    public function testBodyInvariant(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You cannot use "body" and "body_loc_key" at the same time');

        $this->model->setBodyLocKey('body-loc-key');
        $this->model->setBody('mock body');
    }

    public function testTitleLocInvariant(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You cannot use "title" and "title_loc_key" at the same time');

        $this->model->setTitle('mock title');
        $this->model->setTitleLocKey('title-loc-key');
    }

    public function testBodyLocInvariant(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You cannot use "body" and "body_loc_key" at the same time');

        $this->model->setBody('mock body');
        $this->model->setBodyLocKey('body-loc-key');
    }

}
