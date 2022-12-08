<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm;

use ErickSkrauch\Fcm\Exception\UnexpectedResponseException;
use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Recipient;
use ErickSkrauch\Fcm\Response\SendResponse;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Client implements ClientInterface {

    private string $apiKey;

    private HttpClientInterface $httpClient;

    private HttpRequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    /**
     * @param string $apiKey read how to obtain an api key here: https://firebase.google.com/docs/server/setup#prerequisites
     * @param HttpClientInterface|null $httpClient
     * @param HttpRequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null $streamFactory
     */
    public function __construct(
        string $apiKey,
        HttpClientInterface $httpClient = null,
        HttpRequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function send(Message $message, Recipient $recipient): SendResponse {
        $json = $message->getPayload();
        $json[$recipient->getConditionParam()] = $recipient->getConditionValue();

        $request = $this->requestFactory->createRequest('POST', 'https://fcm.googleapis.com/fcm/send');
        $request = $request->withHeader('Authorization', "key={$this->apiKey}");
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($this->streamFactory->createStream(json_encode($json, JSON_THROW_ON_ERROR)));

        $response = $this->httpClient->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new UnexpectedResponseException($response);
        }

        $json = json_decode((string)$response->getBody(), true, JSON_THROW_ON_ERROR);

        return new SendResponse(
            $json['multicast_id'],
            $json['success'],
            $json['failure'],
            $json['results'],
        );
    }

}
