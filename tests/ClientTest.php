<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests;

use ErickSkrauch\Fcm\Client;
use ErickSkrauch\Fcm\Exception\UnexpectedResponseException;
use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Recipient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Strategy\MockClientStrategy;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;

/**
 * @covers \ErickSkrauch\Fcm\Client
 */
final class ClientTest extends TestCase {

    public function testSuccessfullySend(): void {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn(Stream::create('{"multicast_id":1234567890123456789,"success":2,"failure":1,"canonical_ids":0,"results":[{"message_id":"mock_message_1"},{"message_id":"mock_message_2"},{"error":"InvalidRegistration"}]}'));

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
                    '{"collapse_key":"mock collapse key","mock condition param":"mock condition value"}',
                    (string)$request->getBody(),
                );

                return true;
            }))
            ->willReturn($response);

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

        $message = new Message();
        $message->setCollapseKey('mock collapse key');

        $recipient = $this->createMock(Recipient::class);
        $recipient->method('getConditionParam')->willReturn('mock condition param');
        $recipient->method('getConditionValue')->willReturn('mock condition value');

        $client = new Client('mock api key', $httpClient, $requestFactory, $streamFactory);
        $result = $client->send($message, $recipient);

        $this->assertSame(1234567890123456789, $result->multicastId);
        $this->assertSame(2, $result->countSuccess);
        $this->assertSame(1, $result->countFailures);
        $this->assertSame(
            [
                ['message_id' => 'mock_message_1'],
                ['message_id' => 'mock_message_2'],
                ['error' => 'InvalidRegistration'],
            ],
            $result->results,
        );
    }

    public function testUnexpectedResponse(): void {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(401);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn($response);

        $recipient = $this->createMock(Recipient::class);
        $recipient->method('getConditionParam')->willReturn('mock condition param');
        $recipient->method('getConditionValue')->willReturn('mock condition value');

        $client = new Client('mock api key', $httpClient);
        try {
            $client->send(new Message(), $recipient);
            $this->fail(UnexpectedResponseException::class . ' was not thrown');
        } catch (UnexpectedResponseException $e) {
            $this->assertSame('Received an unexpected response from FCM', $e->getMessage());
            $this->assertSame($response, $e->getResponse());
        }
    }

    public function testHttpClientException(): void {
        $exception = $this->createMock(ClientExceptionInterface::class);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willThrowException($exception);

        $recipient = $this->createMock(Recipient::class);
        $recipient->method('getConditionParam')->willReturn('mock condition param');
        $recipient->method('getConditionValue')->willReturn('mock condition value');

        $client = new Client('mock api key', $httpClient);
        try {
            $client->send(new Message(), $recipient);
            $this->fail(ClientExceptionInterface::class . ' was not thrown');
        } catch (Throwable $thrownException) {
            $this->assertSame($exception, $thrownException);
        }
    }

    public function testAutoDiscoveryOfHttpDependencies(): void {
        HttpClientDiscovery::prependStrategy(MockClientStrategy::class);

        $this->expectNotToPerformAssertions();
        new Client('mock api token');
    }

}
