<?php

namespace Kolay;

use Psr\Http\Message\RequestInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\Bearer;
use Http\Client\Common\Exception\ClientErrorException;

class KolayAuthMethodUserCredentials
{

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * KolayAuthMethodUserCredentials constructor.
     *
     * @param array $options Authentication parameters to be used through out the request.
     */
    public function __construct($authParams = [])
    {
        if (isset($authParams['username']) && isset($authParams['password'])) {
            $this->username = $authParams['username'];
            $this->password = $authParams['password'];
        } else {
            throw new Exception('Invalid authentication parameters.');
        }
    }

    public function sign($request)
    {
		$client = new KolayClient(['public' => true]);
		$response = $client->post('public/authenticate', [
			'username' => $this->username,
			'password' => $this->password,
            'source' => 'api'
		]);

        if ($response instanceof KolayErrorResponse) {
            throw new \Exception($response->message, $response->code);
        } 
    	return new Bearer($response->auth_token);
    }
}