<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests;

use ErickSkrauch\Fcm\Client;
use ErickSkrauch\Fcm\Message;
use ErickSkrauch\Fcm\Notification;
use ErickSkrauch\Fcm\Recipient\Recipient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Strategy\MockClientStrategy;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @covers \ErickSkrauch\Fcm\Client
 */
final class ClientTest extends TestCase {

    public function testSuccessfullySend(): void {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function(RequestInterface $request): bool {
                $this->assertSame('POST', $request->getMethod());
                $this->assertSame('https://fcm.googleapis.com/fcm/send', (string)$request->getUri());
                $this->assertSame('key=mock api key', $request->getHeaderLine('Authorization'));
                $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertSame(
                    '{"notification":{"title":"mock title","body":"mock body"},"collapse_key":"mock collapse key","mock condition param":"mock condition value"}',
                    (string)$request->getBody(),
                );

                return true;
            }));

        $requestFactory = $this->createMock(HttpRequestFactoryInterface::class);
        $requestFactory
            ->expects($this->atLeastOnce())
            ->method('createRequest')
            ->willReturnCallback(fn(...$args) => new Request(...$args));
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $streamFactory
            ->expects($this->atLeastOnce())
            ->method('createStream')
            ->willReturnCallback(fn(...$args) => Stream::create(...$args));

        $notification = new Notification();
        $notification->setTitle('mock title');
        $notification->setBody('mock body');
        $message = new Message($notification);
        $message->setCollapseKey('mock collapse key');

        $recipient = $this->createMock(Recipient::class);
        $recipient->method('getConditionParam')->willReturn('mock condition param');
        $recipient->method('getConditionValue')->willReturn('mock condition value');

        $client = new Client('mock api key', $httpClient, $requestFactory, $streamFactory);
        $client->send($message, $recipient);
    }

    public function testAutoDiscoveryOfHttpDependencies(): void {
        HttpClientDiscovery::prependStrategy(MockClientStrategy::class);

        $this->expectNotToPerformAssertions();
        new Client('mock api token');
    }

}
