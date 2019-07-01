<?php

namespace Kolay;

use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Discovery\HttpClientDiscovery;
use Http\Client\Common\PluginClient;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class KolayClient
{
    /**
     * @var string $baseUrl
     */
    private $baseUrl = 'https://kolayik.com/api/v';

    /**
     * @var string $apiVersion
     */
    private $apiVersion = '1';

    /**
     * @var HttpClient $httpClient
     */
    private $httpClient;

    /**
     * @var RequestFactory $requestFactory
     */
    private $requestFactory;

    /**
     * @var UriFactory $uriFactory
     */
    private $uriFactory;

    /**
     * @var object Authentication method
     */
    private $authenticator;

    /**
     * KolayClient constructor.
     *
     * @param array $options Connection options to be used through out the request.
     */
    public function __construct($options = [])
    {
        if (isset($options['userCredentials'])) {
            $this->authenticator = new KolayAuthMethodUserCredentials($options['userCredentials']);
        } elseif (isset($options['public'])) {
            $this->authenticator = null;
        } else {
            throw new Exception('Invalid authentication method.');
        }

        $this->baseUrl = $this->baseUrl . $this->apiVersion . '/';

        $this->httpClient = $this->getDefaultHttpClient();
        $this->requestFactory = MessageFactoryDiscovery::find();
        $this->uriFactory = UriFactoryDiscovery::find();
    }

    /**
     * Sets the HTTP client.
     *
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sets the request factory.
     *
     * @param RequestFactory $requestFactory
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Sets the URI factory.
     *
     * @param UriFactory $uriFactory
     */
    public function setUriFactory(UriFactory $uriFactory)
    {
        $this->uriFactory = $uriFactory;
    }

    /**
     * Sends POST request to Kolay API.
     *
     * @param  string $endpoint
     * @param  array $params
     * @return stdClass
     */
    public function post($endpoint, $params)
    {
        $response = $this->sendRequest('POST', $this->baseUrl . $endpoint, $params);
        return $this->handleResponse($response);
    }

    /**
     * Sends PUT request to Kolay API.
     *
     * @param  string $endpoint
     * @param  array $params
     * @return stdClass
     */
    public function put($endpoint, $params)
    {
        $response = $this->sendRequest('PUT', $this->baseUrl . $endpoint, $params);
        return $this->handleResponse($response);
    }

    /**
     * Sends DELETE request to Kolay API.
     *
     * @param  string $endpoint
     * @param  array $params
     * @return stdClass
     */
    public function delete($endpoint, $params)
    {
        $response = $this->sendRequest('DELETE', $this->baseUrl . $endpoint, $params);
        return $this->handleResponse($response);
    }

    /**
     * Sends GET request to Kolay API.
     *
     * @param string $endpoint
     * @param array  $queryParams
     * @return stdClass
     */
    public function get($endpoint, $queryParams = [])
    {
        $uri = $this->uriFactory->createUri($this->baseUrl . $endpoint);
        if (!empty($queryParams)) {
            $uri = $uri->withQuery(http_build_query($queryParams));
        }

        $response = $this->sendRequest('GET', $uri);

        return $this->handleResponse($response);
    }

    /**
     * @return HttpClient
     */
    private function getDefaultHttpClient()
    {
        return new PluginClient(
            HttpClientDiscovery::find(),
            [new ErrorPlugin()]
        );
    }

    /**
     * @return array
     */
    private function getRequestHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Returns authenticator
     *
     * @return Authentication
     */
    private function getAuthenticator()
    {
        if (!empty($this->authenticator)) {
            return $this->authenticator;
        }
        return null;
    }

    /**
     * Authenticates a request object
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    private function signRequest(RequestInterface $request)
    {
        $authenticator = $this->getAuthenticator();
        return $authenticator ? $authenticator->sign($request)->authenticate($request) : $request;
    }

    /**
     * @param string $method
     * @param string|UriInterface $uri
     * @param array|string|null $body
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    private function sendRequest($method, $uri, $body = null)
    {
        $headers = $this->getRequestHeaders();
        $body = is_array($body) ? json_encode($body) : $body;

        try {
            $request = $this->signRequest(
                $this->requestFactory->createRequest($method, $uri, $headers, $body)
            );

            return $this->httpClient->sendRequest($request);
        } catch (ClientErrorException $e) {
            return $e->getResponse();
        }
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     */
    private function handleResponse(ResponseInterface $response)
    {
        $stream = $response->getBody()->getContents();
        $response = json_decode($stream);

        if ($response->error) {
            return new KolayErrorResponse($response->code, $response->message, $response->details);
        }

        return $response->data;
    }
}
