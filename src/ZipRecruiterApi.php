<?php

namespace ZipRecruiter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\StreamInterface;
use ZipRecruiter\Query;

class ZipRecruiterApi
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Instantiate class with minimal fields to be make valid request
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->client->setDefaultOption('headers/User-Agent', 'caseyw/ziprecruiter-php');
    }

    /**
     * Get Client
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Return last response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Subscribe user to Zip Recruiter
     *
     * @param string $email
     * @param string $search
     * @param string $location
     * @param string|null $name
     * @param string|null $ipAddress
     * @param string|null $brand
     * @param string|null $createTime
     *
     * @return array
     */
    public function subscribe($email, $search, $location, $name = null, $ipAddress = null, $brand = null, $createTime = null)
    {

        $request = [
            'email' => $email,
            'search' => $search,
            'location' => $location,
        ];

        if (!is_null($name)) {
            $request['name'] = $name;
        }

        if (!is_null($ipAddress)) {
            $request['ip_address'] = $ipAddress;
        }

        if (!is_null($brand)) {
            $request['brand'] = $brand;
        }

        if (!is_null($createTime)) {
            $request['create_time'] = $createTime;
        }

        $this->response = $this->client->post('/subscriber', ['body' => $request]);

        if (201 == $this->response->getStatusCode()) {
            return $this->response->json();
        }

        return false;
    }

    /**
     * Query API, and for my needs now we're going to loop through any pages.
     * We'll compile a list of results, and return those.
     *
     * @param \ZipRecruiter\Query $query
     *
     * @return bool|StreamInterface|null
     */
    public function query(Query $query)
    {
        $this->response = $this->client->get('/subscriber', [
            'query' => $query->toArray()
        ]);

        if (200 == $this->response->getStatusCode()) {
            return json_decode($this->response->getBody()->getContents(), true);
        }

        return false;
    }

    /**
     * Deactivate Subscription for ID
     *
     * @param string $id
     *
     * @return string|false
     */
    public function deactivate($id)
    {
        $url = '/subscribe/' . $id;

        $this->response = $this->client->delete($url);

        if (200 == $this->response->getStatusCode()) {
            return $this->response->json();
        }

        return false;
    }

}
