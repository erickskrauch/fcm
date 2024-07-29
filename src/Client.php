<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm;

use ErickSkrauch\Fcm\Exception\ErrorResponseException;
use ErickSkrauch\Fcm\Exception\UnexpectedResponseException;
use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Recipient\Recipient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Client implements ClientInterface {

    private string $apiKey;

    private string $projectName;

    private HttpClientInterface $httpClient;

    private HttpRequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    /**
     * @param string $oauthToken you must generate the JWT key for your service account with a scope "https://www.googleapis.com/auth/firebase.messaging"
     * @param string $projectId you can find the project's ID here: https://console.firebase.google.com. Copy the gray text under the project name. For example "myapp-5427d"
     */
    public function __construct(
        string $oauthToken,
        string $projectId,
        ?HttpClientInterface $httpClient = null,
        ?HttpRequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->apiKey = $oauthToken;
        $this->projectName = $projectId;
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function send(Message $message, Recipient $recipient): string {
        $json = $message->getPayload();
        $json[$recipient->getConditionParam()] = $recipient->getConditionValue();

        $request = $this->requestFactory->createRequest('POST', "https://fcm.googleapis.com/v1/projects/{$this->projectName}/messages:send");
        $request = $request->withHeader('Authorization', "Bearer {$this->apiKey}");
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($this->streamFactory->createStream(json_encode(['message' => $json], JSON_THROW_ON_ERROR)));

        $response = $this->httpClient->sendRequest($request);
        if (!in_array($response->getStatusCode(), [200, 400], true)) {
            throw new UnexpectedResponseException("Received unexpected FCM response with {$response->getStatusCode()} status code", $response);
        }

        $json = json_decode((string)$response->getBody(), true, JSON_THROW_ON_ERROR);
        if (isset($json['error'])) {
            throw new ErrorResponseException($json['error']['message'], $json['error']['status'], $response);
        }

        return $json['name'];
    }

}
