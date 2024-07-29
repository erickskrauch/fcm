<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Tests;

use ErickSkrauch\Fcm\Client;
use ErickSkrauch\Fcm\Exception\ErrorResponseException;
use ErickSkrauch\Fcm\Exception\UnexpectedResponseException;
use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Device;
use ErickSkrauch\Fcm\Recipient\Recipient;
use Http\Mock\Client as MockHttpClient;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \ErickSkrauch\Fcm\Client
 * @covers \ErickSkrauch\Fcm\Exception\ErrorResponseException
 * @covers \ErickSkrauch\Fcm\Exception\UnexpectedResponseException
 */
final class ClientTest extends TestCase {

    private MockHttpClient $httpClient;

    private Client $client;

    protected function setUp(): void {
        parent::setUp();
        $this->httpClient = new MockHttpClient();
        $this->client = new Client('mock api token', 'mock-project', $this->httpClient);
    }

    public function testSuccessfullySend(): void {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn(Stream::create('{"name":"projects/mock-project/messages/0:1722275862463685%d2fce111d2fce111"}'));

        $this->httpClient->addResponse($response);

        $message = new Message();
        $message->setCollapseKey('mock collapse key');

        $recipient = $this->createMock(Recipient::class);
        $recipient->method('getConditionParam')->willReturn('mock condition param');
        $recipient->method('getConditionValue')->willReturn('mock condition value');

        $this->assertSame('projects/mock-project/messages/0:1722275862463685%d2fce111d2fce111', $this->client->send($message, $recipient));

        $sentRequests = $this->httpClient->getRequests();
        $this->assertCount(1, $sentRequests);
        [$sentRequest] = $sentRequests;
        $this->assertSame('POST', $sentRequest->getMethod());
        $this->assertSame('https://fcm.googleapis.com/v1/projects/mock-project/messages:send', (string)$sentRequest->getUri());
        $this->assertSame('Bearer mock api token', $sentRequest->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $sentRequest->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"message":{"collapse_key":"mock collapse key","mock condition param":"mock condition value"}}',
            (string)$sentRequest->getBody(),
        );
    }

    public function testValidationException(): void {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);
        $response->method('getBody')->willReturn(Stream::create('{"error":{"code":400,"message":"Some error","status":"INVALID_ARGUMENT","details":[{"@type":"type.googleapis.com/google.firebase.fcm.v1.FcmError","errorCode":"INVALID_ARGUMENT"}]}}'));

        $this->httpClient->addResponse($response);

        try {
            $this->client->send(new Message(), new Device(''));
            $this->fail(ErrorResponseException::class . ' was not thrown');
        } catch (ErrorResponseException $e) {
            $this->assertSame('Some error', $e->getMessage());
            $this->assertSame(400, $e->getCode());
            $this->assertSame('INVALID_ARGUMENT', $e->errorCode);
            $this->assertSame($response, $e->response);
        }
    }

    public function testUnexpectedResponse(): void {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);

        $this->httpClient->addResponse($response);

        try {
            $this->client->send(new Message(), new Device(''));
            $this->fail(UnexpectedResponseException::class . ' was not thrown');
        } catch (UnexpectedResponseException $e) {
            $this->assertSame('Received unexpected FCM response with 500 status code', $e->getMessage());
            $this->assertSame(500, $e->getCode());
            $this->assertSame($response, $e->response);
        }
    }

}
